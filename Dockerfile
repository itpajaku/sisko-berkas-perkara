# Gunakan base image PHP 7.4 dengan Apache
FROM php:7.4-apache

# Install ekstensi PHP umum
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Aktifkan mod_rewrite (untuk routing CI3)
RUN a2enmod rewrite

# Set document root ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update konfigurasi Apache sesuai document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Salin semua file aplikasi ke dalam container
COPY . /var/www/html

# Set permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
