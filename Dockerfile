# Use the official PHP image as the base image
FROM php:8.2-apache

# Set the working directory inside the container
WORKDIR /var/www/html/track_n_trace

# Install dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring zip

# Enable Apache rewrite module` 
RUN a2enmod rewrite

# Copy custom Apache configuration file to the container
COPY custom-apache.conf /etc/apache2/sites-available/laravel.conf

# Copy the project files to the container
COPY . /var/www/html/track_n_trace

# Set ownership permissions
RUN chown -R www-data:www-data /var/www/html/track_n_trace
RUN chmod -R 775 /var/www/html/track_n_trace/storage

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php

RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Install project dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel key
RUN php artisan key:generate

# Run migrations
RUN php artisan migrate

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
