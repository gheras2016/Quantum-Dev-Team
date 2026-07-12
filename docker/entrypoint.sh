#!/bin/sh
# Container start-up: render the Nginx config for the assigned port, warm the
# framework caches with the live environment, run migrations, then hand off to
# the CMD (supervisord).
set -e

# Render provides $PORT; default to 8080 for local `docker run`.
: "${PORT:=8080}"
export PORT
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
mkdir -p /run/nginx

cd /var/www/html

# Public storage symlink for user-uploaded files.
php artisan storage:link 2>/dev/null || true

# Cache configuration, routes, views and events using the real environment.
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Apply database migrations. Set RUN_MIGRATIONS=false to skip (e.g. multi-instance).
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || echo "WARNING: migrations failed, continuing startup."
fi

# Hand the writable dirs (incl. anything cached above as root) to the PHP-FPM user.
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

exec "$@"
