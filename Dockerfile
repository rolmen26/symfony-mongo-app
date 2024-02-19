FROM php:8.1-fpm-alpine3.18 as backend

ARG user=www-data
ARG group=www-data

RUN apk update && apk add --no-cache nginx libpng-dev openssl-dev libxml2-dev curl-dev $PHPIZE_DEPS \
    libzip libzip-dev \
    php-xmlwriter php-tokenizer && \
    # Install mongodb
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install gd sockets && \
    # Install xdebug
    apk add --no-cache linux-headers && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del --no-cache $PHPIZE_DEPS && \
    rm -rf /var/cache/apk/*

WORKDIR /app

COPY --chown={$user}:{$group} . /app

# Composer
COPY --from=composer@sha256:2dc4166e6ef310e16a9ab898e6bd5d088d1689f75f698559096d962b12c889cc /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

#Run Composer
RUN composer install --prefer-dist

RUN mkdir -p /app/var \
    && chmod 777 -R /app/var \
    && chown -R ${user}:${group} /app/var

# Copy the Nginx config file
COPY etc/docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY etc/docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY etc/docker/php/php.ini /usr/local/etc/php/php.ini

# Expose port 80 for HTTP traffic
EXPOSE 80

RUN mkdir -p /run/nginx \
    && chown -R ${user}:${group} /run/nginx

# Set the command to run when the container starts
#ENTRYPOINT [ "supervisord" ]

#CMD ["-n", "-c", "/etc/supervisor/supervisord.conf"]

CMD sh -c "php-fpm -D && nginx -g 'daemon off;'"