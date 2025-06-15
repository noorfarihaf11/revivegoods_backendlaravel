FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libzip-dev libonig-dev libxml2-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mysqli zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel setup: clear cache only during build
RUN php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear \
 && php artisan storage:link

# Expose Laravel port
EXPOSE 8080

# Start the app and generate fresh config cache at runtime
CMD php artisan config:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
