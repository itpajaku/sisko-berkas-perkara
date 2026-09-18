FROM php:8.2-apache

# Install dependencies sistem dan build tools
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        zip \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Salin konfigurasi VirtualHost Apache
COPY vhost.conf /etc/apache2/sites-available/ci3.conf
RUN a2dissite 000-default.conf && a2ensite ci3.conf

# Salin Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin composer files terlebih dahulu untuk caching layer Docker
COPY composer.json composer.lock* ./

# Install dependencies via composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || composer update --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Simpan backup vendor untuk bootstrap jika volume mount host belum memiliki vendor
RUN cp -rp /var/www/html/vendor /var/www/vendor-cache 2>/dev/null || true

# Salin seluruh source code project
COPY . .

# Siapkan direktori log, cache dan permission
RUN mkdir -p application/logs application/cache doc/output \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 application/logs application/cache doc/output

# Salin dan siapkan script entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
