FROM php:8.1-fpm-alpine3.17

RUN apk update && apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev pkgconfig \
    zip \
    unzip \
    autoconf \
    bash \
    nginx \
    gcc g++ make \
    openssl \
    supervisor && \
    pecl install mongodb && \
    echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini-development && \
    docker-php-ext-enable mongodb && \
    docker-php-ext-install gd sockets && \
    rm -rf /var/cache/apk/*


WORKDIR /var/www/html

COPY . .

# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

#Run Composer
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction --optimize-autoloader

# Copy the Nginx config file
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/php/fpm-pool.conf /usr/local/etc/php-fpm.d/fpm-pool.conf
COPY ./docker/supervisor/supervisord.conf /etc/supervisord.conf

RUN chmod -R 777 ./

# Expose port 80 for HTTP traffic
EXPOSE 80

# Set the command to run when the container starts
RUN echo user=root >>  /etc/supervisord.conf
CMD ["/usr/bin/supervisord","-n"]
