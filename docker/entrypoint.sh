#!/bin/sh

composer install --no-interaction --optimize-autoloader

if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
    php artisan key:generate
fi

if [ ! -f /var/www/database/sqlite/database.sqlite ]; then
    touch /var/www/database/sqlite/database.sqlite
fi

php artisan migrate --force

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database/sqlite

php-fpm
