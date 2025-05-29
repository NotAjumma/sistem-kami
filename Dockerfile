FROM php:8.2-cli

# Pasang system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Pasang composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Salin semua fail projek
COPY . .

# Pasang composer dependencies
RUN composer install --no-dev --optimize-autoloader
npm install && npm run build

# Generate APP_KEY kalau tiada
RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
    php artisan key:generate

# Betulkan permission untuk folder penting
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# Expose port (Railway inject ${PORT})
EXPOSE 8080

# Jalankan Laravel dengan port dinamik
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
