# syntax=docker/dockerfile:1
#
# Production image for the Quantum Dev Team Laravel app.
#
# A shared `base` stage fixes the PHP version (8.2) and compiles every required
# extension ONCE, so the Composer stage and the runtime stage are guaranteed to
# use the same PHP + extensions. Composer itself is copied in as a PHAR binary
# and therefore runs under this image's PHP 8.2 — never the `composer` image's
# own (currently 8.5) PHP.
#
# The container listens on $PORT (Render injects it; defaults to 8080 locally).

############################################
# 0) Base: PHP 8.2 + all required extensions (shared by build & runtime)
############################################
FROM php:8.2-fpm-alpine AS base

# Runtime libraries stay in the image; the -dev headers are installed in a
# throw-away virtual package and removed after the extensions are built.
RUN set -eux; \
    apk add --no-cache \
        postgresql-libs libpng libjpeg-turbo freetype libwebp libzip icu-libs oniguruma; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS postgresql-dev libpng-dev libjpeg-turbo-dev freetype-dev \
        libwebp-dev libzip-dev icu-dev oniguruma-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
    docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql pgsql gd zip mbstring bcmath exif pcntl intl opcache; \
    apk del .build-deps

# Pinned Composer binary (PHAR). It executes with the PHP of the image it runs
# in (PHP 8.2 here), so the composer image's own PHP version is irrelevant.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

############################################
# 1) PHP dependencies (Composer) — same PHP 8.2 + extensions as runtime
############################################
FROM base AS vendor
WORKDIR /app

# git/unzip let Composer fetch & extract dist packages reliably.
RUN apk add --no-cache git unzip

# Copy only the manifests first so this layer is cached until they change.
COPY composer.json composer.lock ./

# Install production dependencies. Scripts (package:discover) are skipped here
# and re-run in the runtime stage once the full application code is present.
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
FROM base AS app

# Web server + process manager + envsubst (for templating the Nginx port).
RUN apk add --no-cache nginx supervisor gettext \
 && mkdir -p /run/nginx

WORKDIR /var/www/html

# ---- Application code, dependencies and compiled assets ------------------
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# Regenerate an authoritative autoloader and run package discovery now that
# every runtime extension is available (this is why --no-scripts was used above).
RUN composer dump-autoload --no-dev --classmap-authoritative \
 && php artisan package:discover --ansi

# ---- Service configuration ----------------------------------------------
COPY docker/php/php.ini                 /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/www.conf               /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/nginx/nginx.conf.template   /etc/nginx/nginx.conf.template
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# ---- Writable directories & permissions ---------------------------------
RUN mkdir -p storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod +x docker/entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
