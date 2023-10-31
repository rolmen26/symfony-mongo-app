FROM php:8.1-fpm-alpine3.18 as backend

ARG user=www-data
ARG group=www-data

RUN apk update && apk add --no-cache nginx supervisor libpng-dev && \
    apk add --no-cache $PHPIZE_DEPS && \
    # Install mongodb
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install gd sockets && \
    # Install xdebug
    apk add --no-cache linux-headers && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    rm -rf /var/cache/apk/*

WORKDIR /app

COPY --chown={$user}:{$group} . /app

# Composer
COPY --from=composer@sha256:2dc4166e6ef310e16a9ab898e6bd5d088d1689f75f698559096d962b12c889cc /usr/bin/composer /usr/bin/composer
ENV COMPOSER_HOME /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

#Run Composer
RUN composer install --prefer-dist

# Copy the Nginx config file
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/php/fpm-pool.conf /usr/local/etc/php-fpm.d/fpm-pool.conf
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Expose port 80 for HTTP traffic
EXPOSE 80

# Set the command to run when the container starts
ENTRYPOINT [ "supervisord" ]

CMD ["-n", "-c", "/etc/supervisor/supervisord.conf"]