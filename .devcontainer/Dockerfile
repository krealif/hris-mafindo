ARG PHP_VERSION=8.3
ARG BASE_IMAGE="docker.io/dunglas/frankenphp:php${PHP_VERSION}-alpine"

FROM ${BASE_IMAGE}

# Create the user
ARG USERNAME=vscode
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN addgroup -g $USER_GID $USERNAME \
  && adduser -G $USERNAME -u $USER_UID $USERNAME -D \
  && apk add --no-cache sudo \
  && echo "$USERNAME ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# Install php modules
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions zip pdo_mysql pdo_pgsql gd intl opcache xdebug

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php \
  && chmod +x composer.phar \
  && mv composer.phar /usr/local/bin/composer

# Install other packages.
RUN apk add --no-cache supervisor bash fish curl git nodejs npm openssh-client ca-certificates

RUN mkdir -p /config /data \
  && chown -R $USER_UID:$USER_GID /config /data

# Copy configuration files
COPY ./php/docker-php-ext-xdebug.ini \
  ./php/docker-php-ext-opcache.ini \
  /usr/local/etc/php/conf.d/
COPY ./caddy/Caddyfile /config/Caddyfile
COPY ./supervisord.conf /etc/supervisord.conf
