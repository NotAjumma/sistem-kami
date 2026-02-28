FROM php:8.2-cli

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev zip ca-certificates \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install npm & build
RUN npm install && npm run build

# Create required folders & fix permissions BEFORE cache clear
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/app \
    && chmod -R 775 storage bootstrap/cache public/app \
    && chown -R www-data:www-data storage bootstrap/cache public public/app

# Clear cache after permissions fixed
# RUN php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear

# Copy entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
