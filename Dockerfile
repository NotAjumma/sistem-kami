FROM php:8.2-cli

# Install system dependencies and Node.js
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev zip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy source
COPY . .

# Composer install
RUN composer install --no-dev --optimize-autoloader

# Node modules and build assets
RUN npm install && npm run build

# Generate APP_KEY if not exists
# RUN php artisan key:generate

# Link storage and fix permissions
RUN php artisan storage:link || true

# Fix permissions - combine chown and chmod in one go
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
