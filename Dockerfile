# syntax=docker/dockerfile:1
#
# Production image for the Quantum Dev Team Laravel app.
# Three stages keep the final image small:
#   1) vendor  – installs PHP dependencies with Composer
#   2) assets  – compiles the front-end (Vite + Tailwind) into public/build
#   3) app     – the runtime: PHP-FPM + Nginx + Supervisor on Alpine
#
# The container listens on $PORT (Render injects it; defaults to 8080 locally).

############################################
# 1) PHP dependencies (Composer)
############################################
FROM composer:2 AS vendor
WORKDIR /app

# Copy only the manifests first so this layer is cached until they change.
COPY composer.json composer.lock ./

# Install production dependencies. Scripts (package:discover) are skipped here
# because the application code is not present yet and the runtime extensions
# (pgsql, gd, …) live in the final stage — discovery is re-run there.
RUN composer install \
        --no-dev \
        --no-scripts \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --optimize-autoloader

############################################
# 2) Front-end assets (Vite / Tailwind)
############################################
FROM node:20-alpine AS assets
WORKDIR /app

COPY package*.json ./
RUN npm install --no-audit --no-fund

# The whole project is copied so Tailwind can scan every Blade template
# referenced by its `content` globs when purging unused CSS.
COPY . .
RUN npm run build

############################################
# 3) Production runtime (PHP-FPM + Nginx)
############################################
FROM php:8.1-fpm-alpine AS app

# ---- System packages & PHP extensions -----------------------------------
# Runtime libraries stay in the image; the -dev headers are installed in a
# throw-away virtual package and removed after the extensions are built.
RUN set -eux; \
    apk add --no-cache \
        nginx supervisor gettext \
        postgresql-libs libpng libjpeg-turbo freetype libwebp libzip icu-libs oniguruma; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS postgresql-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        libwebp-dev libzip-dev icu-dev oniguruma-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql pgsql gd zip mbstring bcmath exif pcntl intl opcache; \
    apk del .build-deps; \
    mkdir -p /run/nginx

# Composer binary is kept so artisan/composer commands work at build & runtime.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---- Application code, dependencies and compiled assets ------------------
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Regenerate an authoritative autoloader and run package discovery now that
# every runtime extension is available.
RUN composer dump-autoload --no-dev --classmap-authoritative \
 && php artisan package:discover --ansi

# ---- Service configuration ----------------------------------------------
COPY docker/php/php.ini              /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf            /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/nginx/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# ---- Writable directories & permissions ---------------------------------
RUN mkdir -p storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod +x docker/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
