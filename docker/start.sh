#!/bin/sh
set -e
php artisan migrate --force
chown -R www-data:www-data storage bootstrap/cache
php-fpm -D
nginx -g "daemon off;"