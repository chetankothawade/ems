FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy source
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# Apache config
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80
