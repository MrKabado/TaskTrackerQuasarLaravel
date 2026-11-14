#!/bin/sh
set -eu

port="${PORT:-10000}"

case "${RUN_MIGRATIONS:-false}" in
    true)
        php artisan migrate --force
        ;;
    false)
        ;;
    *)
        echo "RUN_MIGRATIONS must be set to true or false." >&2
        exit 1
        ;;
esac

php artisan config:cache
php artisan view:cache
sed -ri "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
