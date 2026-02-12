FROM php:8.2-cli

# Install system deps
RUN apt-get update && apt-get install -y \
    unzip git curl zip ca-certificates \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Node 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ------------------------------
# 1️⃣ COPY ONLY DEP FILES FIRST
# ------------------------------

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY package.json package-lock.json ./
RUN npm ci

# ------------------------------
# 2️⃣ COPY PROJECT FILES
# ------------------------------

COPY . .

# Build frontend
RUN npm run build

# Clear cache
RUN php artisan config:clear && php artisan route:clear

# Permissions
RUN mkdir -p public/app \
    && chmod -R 775 storage bootstrap/cache public/app \
    && chown -R www-data:www-data storage bootstrap/cache public public/app

# Entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
