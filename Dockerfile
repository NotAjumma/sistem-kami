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
    # install Node.js and npm dependencies
    ca-certificates \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Node.js (latest LTS) and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Pasang composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Salin semua fail projek
COPY . .

# Pasang composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Pasang npm dependencies dan build assets
RUN npm install && npm run build

# Generate APP_KEY kalau tiada
RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
    php artisan key:generate

RUN php artisan config:clear && php artisan cache:clear && php artisan config:cache
RUN php artisan migrate

# Betulkan permission untuk folder penting
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# Expose port (Railway inject ${PORT})
EXPOSE 8080

# Jalankan Laravel dengan port dinamik
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
