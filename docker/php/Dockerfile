
FROM php:8.2-apache

# 1. Install dependensi sistem dan extension PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# 2. Install extension PHP untuk MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd

# 3. Aktifkan Apache mod_rewrite untuk Laravel Routing
RUN a2enmod rewrite

# untuk htacces,di file /website/include supaya tidak bisa di buka dari website
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# 4. Install Composer (Untuk install Laravel nantinya)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 5. Beri izin akses ke user apache
RUN chown -R www-data:www-data /var/www/html