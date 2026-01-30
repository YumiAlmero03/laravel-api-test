# Use the official PHP image with FPM
FROM php:8.4-fpm

# Set the working directory in the container
WORKDIR /var/www/html

# Set environment variables
ENV APP_ENV=production \
    APP_DEBUG=false

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

# Copy the Laravel project into the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader


# Set file permissions for the Laravel project
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 777 /var/www/html/storage \
#     && chmod -R 777 /var/www/html/bootstrap/cache

# RUN chown -R www-data:www-data /var/www/html/storage

# RUN php artisan config:clear
# RUN php artisan cache:clear
# RUN php artisan route:clear
# RUN php artisan view:clear

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start the PHP-FPM server in foreground
CMD ["php-fpm", "-F"]
