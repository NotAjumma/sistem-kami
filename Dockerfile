FROM php:8.2-cli

# Pasang system dependencies yang diperlukan
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

WORKDIR /app

# Copy semua fail source code ke container
COPY . .

# Pasang composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port, fallback 8080 jika PORT tak diset
EXPOSE 8080

# Serve Laravel pada port dinamik, default 8080
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
