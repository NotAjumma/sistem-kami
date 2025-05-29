# Guna PHP 8.2 CLI base image
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    npm \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Salin semua file ke dalam container
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node modules & build Vite assets
RUN npm install && npm run build

# Laravel needs storage links
RUN php artisan storage:link || true

# Expose port 8080
EXPOSE 8080

# Serve Laravel app on dynamic port (default: 8080)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
