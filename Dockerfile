FROM php:8.1-fpm-alpine3.18 as backend

ARG user=www-data
ARG group=www-data

RUN apk update && apk add --no-cache nginx bash libpng-dev openssl-dev libxml2-dev curl-dev $PHPIZE_DEPS \
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
    rm -rf /var/cache/apk/* && \
    # Composer time
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


WORKDIR /app

COPY --chown={$user}:{$group} . /app

# Composer
ENV COMPOSER_HOME /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

#Run Composer
RUN composer install --prefer-dist

RUN mkdir -p /app/var \
    && chmod 777 -R /app/var \
    && chown -R ${user}:${group} /app/var

# Copy the Nginx config file
COPY etc/docker/php/xdebug.ini $PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini
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