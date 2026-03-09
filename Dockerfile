FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    nginx unzip git curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip ca-certificates libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd opcache

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP-FPM config: listen on 127.0.0.1:9000
RUN sed -i 's|listen = /run/php/.*|listen = 127.0.0.1:9000|g' /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true
RUN echo "[www]\nlisten = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-listen.conf

# PHP OPcache for production
RUN echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.max_accelerated_files=10000\nopcache.validate_timestamps=0" > /usr/local/etc/php/conf.d/opcache.ini

# Raise memory limit for image processing (CLI + FPM)
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory.ini

WORKDIR /app

# Copy project
COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install npm & build
RUN npm install && npm run build

# Create required folders & fix permissions
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/app \
    && chmod -R 775 storage bootstrap/cache public/app \
    && chown -R www-data:www-data storage bootstrap/cache public public/app

# Nginx config
COPY nginx.conf /etc/nginx/sites-available/default

# Copy entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
