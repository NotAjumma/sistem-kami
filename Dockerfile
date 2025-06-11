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
RUN composer dump-autoload

# Pasang npm dependencies dan build assets
RUN npm install && npm run build

# Generate APP_KEY kalau tiada
# RUN if [ ! -f .env ]; then cp .env.example .env; fi && \
#     php artisan key:generate
# RUN cp -f .env.example .env && php artisan key:generate --force
# RUN rm -f .env --force
RUN php artisan config:clear && php artisan cache:clear
# RUN php artisan migrate --force

# Create public/app folder to avoid error if missing
RUN mkdir -p public/app

# Then fix permissions (including public/app)
RUN chmod -R 775 storage bootstrap/cache public/app \
    && chown -R www-data:www-data storage bootstrap/cache public public/app

# Copy entrypoint script and give execution permission
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose port (Railway inject ${PORT})
EXPOSE 8080

# Use entrypoint script to start container
ENTRYPOINT ["/entrypoint.sh"]
