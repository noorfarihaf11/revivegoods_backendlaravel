FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel setup
RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear \
 && php artisan storage:link \
 && php artisan config:cache \
 && php artisan route:cache

# Expose port
EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=$(echo $PORT | sed 's/[^0-9]//g')

