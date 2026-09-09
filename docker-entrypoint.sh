#!/bin/bash
set -e

# Buat direktori logs & output jika belum ada serta set permission
mkdir -p /var/www/html/application/logs /var/www/html/doc/output
chown -R www-data:www-data /var/www/html/application/logs /var/www/html/doc/output
chmod -R 775 /var/www/html/application/logs /var/www/html/doc/output

# Pastikan file .env ada untuk kompatibilitas
if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.docker ]; then
    cp /var/www/html/.env.docker /var/www/html/.env
fi

# Tunggu database siap jika DB_HOST didefinisikan
if [ -n "$DB_HOST" ] && [ "$DB_HOST" != "localhost" ]; then
    echo "Menunggu koneksi database di $DB_HOST:3306..."
    until mysqladmin ping -h"$DB_HOST" -u"${DB_USER:-root}" -p"${DB_PASS:-}" --skip-ssl --silent; do
        sleep 2
    done
    echo "Database $DB_HOST siap!"

    # Jalankan migration dan seed jika diaktifkan (default: true)
    if [ "${AUTO_MIGRATE:-true}" = "true" ]; then
        echo "Menjalankan database migration via Phinx..."
        vendor/bin/phinx migrate -e development || true

        if [ "${AUTO_SEED:-true}" = "true" ]; then
            echo "Menjalankan seeder database via Phinx..."
            vendor/bin/phinx seed:run -e development || true
        fi
    fi
fi

# Jalankan perintah CMD Docker (default: apache2-foreground)
exec "$@"
