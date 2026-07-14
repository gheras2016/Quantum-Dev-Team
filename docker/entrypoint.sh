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

# --- Ensure a valid application key ---------------------------------------
# Laravel throws MissingAppKeyException (HTTP 500) on EVERY request when
# APP_KEY is empty, because the EncryptCookies middleware runs for all web
# routes. Prefer an explicitly configured key; if none is provided, generate a
# valid AES-256 key so the app still boots. It is exported BEFORE config:cache
# so the cached config contains it.
if [ -z "${APP_KEY:-}" ]; then
    APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
    export APP_KEY
    echo "WARNING: APP_KEY was not set — generated an ephemeral key for this deploy."
    echo "         Set a persistent APP_KEY in Render to keep sessions valid across deploys."
fi

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

# Seed roles, settings, demo content and the super admin. Set RUN_SEED=true in
# the environment to enable. Seeders are idempotent (firstOrCreate), so it is
# safe to leave on across deploys.
if [ "${RUN_SEED:-false}" = "true" ]; then
    echo "Seeding database..."
    php artisan db:seed --force || echo "WARNING: seeding failed, continuing startup."
fi

# Hand the writable dirs (incl. anything cached above as root) to the PHP-FPM user.
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

exec "$@"
