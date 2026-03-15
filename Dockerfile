FROM php:8.3-fpm

# System dependencies
RUN apt-get update && apt-get install -y \
    nginx unzip git curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev zip ca-certificates libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd opcache \
    && pecl install redis && docker-php-ext-enable redis

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# PHP-FPM config: listen on 127.0.0.1:9000
RUN sed -i 's|listen = /run/php/.*|listen = 127.0.0.1:9000|g' /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true
RUN echo "[www]\nlisten = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-listen.conf

# PHP OPcache + JIT for production
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=512\n\
opcache.interned_strings_buffer=64\n\
opcache.max_accelerated_files=20000\n\
opcache.validate_timestamps=0\n\
opcache.save_comments=1\n\
opcache.enable_file_override=1\n\
opcache.jit=1255\n\
opcache.jit_buffer_size=128M" > /usr/local/etc/php/conf.d/opcache.ini

# PHP-FPM tuning for production
RUN echo "[www]\n\
pm = dynamic\n\
pm.max_children = 50\n\
pm.start_servers = 10\n\
pm.min_spare_servers = 5\n\
pm.max_spare_servers = 20\n\
pm.max_requests = 1000" > /usr/local/etc/php-fpm.d/zz-tuning.conf

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
COPY nginx.conf /etc/nginx/nginx.conf

# Copy entrypoints (web = default, worker = override via Railway start command)
COPY entrypoint-web.sh /entrypoint-web.sh
COPY entrypoint-worker.sh /entrypoint-worker.sh
RUN chmod +x /entrypoint-web.sh /entrypoint-worker.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint-web.sh"]
