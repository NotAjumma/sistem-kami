# ================================
# 1️⃣ Base Image
# ================================
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl zip ca-certificates \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Node 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# ================================
# 2️⃣ Dependency Caching Layer
# ================================

# Copy composer files first
COPY composer.json composer.lock ./

# Copy required Laravel structure (for autoload & scripts)
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY routes ./routes

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copy npm files
COPY package.json package-lock.json ./

# Install JS dependencies (faster & consistent)
RUN npm ci

# ================================
# 3️⃣ Copy Full Project
# ================================
COPY . .

# Build frontend assets
RUN npm run build

# ================================
# 4️⃣ Laravel Optimization
# ================================
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Create required folders
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    public/app

# Fix permissions
RUN chmod -R 775 storage bootstrap/cache public/app \
    && chown -R www-data:www-data storage bootstrap/cache public public/app

# ================================
# 5️⃣ Entrypoint
# ================================
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
