ARG BASE_OS_VERSION=alpine
ARG PHP_VERSION=8.3
ARG BASE_IMAGE="docker.io/php:${PHP_VERSION}-fpm-${BASE_OS_VERSION}"

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
RUN install-php-extensions redis pdo_mysql pdo_pgsql gd intl opcache xdebug

# Install composer
RUN curl -sSL https://getcomposer.org/installer | php \
  && chmod +x composer.phar \
  && mv composer.phar /usr/local/bin/composer

# Install frankenphp
RUN curl https://frankenphp.dev/install.sh | sh \
  && mv frankenphp /usr/local/bin/
  
# Install other packages.
RUN apk add --no-cache supervisor bash fish curl git nodejs npm openssh-client ca-certificates
  
# Copy configuration files
COPY ./php/docker-php-ext-xdebug.ini \
  ./php/docker-php-ext-opcache.ini \
  /usr/local/etc/php/conf.d/
COPY ./caddy/Caddyfile /etc/caddy/Caddyfile
COPY ./supervisord.conf /etc/supervisord.conf
