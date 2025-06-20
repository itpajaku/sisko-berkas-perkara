FROM php:7.4-apache

# Install dependencies dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libonig-dev libxml2-dev libzip-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Salin file konfigurasi vhost ke Apache
COPY vhost.conf /etc/apache2/sites-available/ci3.conf

# Aktifkan virtual host dan nonaktifkan default
RUN a2dissite 000-default.conf && a2ensite ci3.conf

# Buat folder log CI3 jika belum ada
RUN mkdir -p /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html/application/logs \
    && chmod -R 755 /var/www/html/application/logs

# Salin seluruh source code ke dalam container
COPY . /var/www/html
COPY .env /var/www/html/.env

# Set permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN composer install --no-interaction --prefer-dist \
    && composer dump-autoload --optimize

EXPOSE 80

CMD ["apache2-foreground"]
