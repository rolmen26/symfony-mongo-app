ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm-alpine3.18 as backend

ARG user=www-data
ARG group=www-data

RUN apk update && apk add --no-cache \
    nginx \
    libpng-dev \
    openssl-dev \
    libxml2-dev \
    curl-dev \
    linux-headers \
    libzip \
    libzip-dev \
    php-xmlwriter \
    php-tokenizer \
    $PHPIZE_DEPS && \
    pecl install mongodb xdebug && \
    docker-php-ext-enable mongodb xdebug && \
    docker-php-ext-install gd sockets && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    apk del --purge $PHPIZE_DEPS && \
    rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

ENV COMPOSER_HOME=/usr/local/bin/composer \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PATH=$PATH:/usr/local/bin/composer

WORKDIR /app
COPY --chown=${user}:${group} . /app

RUN composer install --prefer-dist

RUN mkdir -p /app/var /run/nginx && \
    chmod 777 -R /app/var && \
    chown -R ${user}:${group} /app /run/nginx

COPY etc/docker/php/xdebug.ini $PHP_INI_DIR/conf.d/
COPY etc/docker/nginx/default.conf /etc/nginx/http.d/
COPY etc/docker/php/php.ini /usr/local/etc/php/

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]