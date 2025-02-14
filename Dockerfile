ARG PHP_VERSION=8.3

FROM serversideup/php:${PHP_VERSION}-fpm-nginx-alpine AS base

# Switch to root before installing our PHP extensions
USER root

RUN install-php-extensions intl

# Drop back to our unprivileged user
USER www-data

FROM base AS deploy

# Environment variables
ENV PHP_OPCACHE_ENABLE="1"
COPY --chmod=755 ./.docker/entrypoint.d/ /etc/entrypoint.d/

WORKDIR /var/www/html
COPY --chown=www-data:www-data . .

RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --no-dev
