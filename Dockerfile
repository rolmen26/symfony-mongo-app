# Usa una imagen base liviana para la construcción
ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-fpm-alpine3.18 as builder

ARG user=www-data
ARG group=www-data

# Instalar dependencias necesarias para construir la aplicación
RUN apk update && apk add --no-cache --virtual .build-deps \
    gcc \
    g++ \
    make \
    autoconf \
    libpng-dev \
    openssl-dev \
    libxml2-dev \
    curl-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd sockets \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establecer variables de entorno para Composer
ENV COMPOSER_HOME=/composer \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PATH=$PATH:/composer/vendor/bin

# Crear y configurar el directorio de caché de Composer
RUN mkdir -p /composer/cache && \
    chmod -R 775 /composer && \
    chown -R ${user}:${group} /composer

WORKDIR /app
COPY --chown=${user}:${group} . /app

# Instalar dependencias de Composer para producción
RUN composer install --no-dev --optimize-autoloader && \
    rm -rf /composer/cache

# Imagen final para producción
FROM php:${PHP_VERSION}-fpm-alpine3.18

ARG user=www-data
ARG group=www-data

# Instalar dependencias necesarias para la ejecución de la aplicación
RUN apk add --no-cache \
    nginx \
    libpng \
    openssl \
    libxml2 \
    curl \
    libzip \
    freetype \
    jpeg

# Copiar configuraciones de PHP y nginx
COPY etc/docker/php/php.ini /usr/local/etc/php/
COPY etc/docker/nginx/default.conf /etc/nginx/http.d/

# Copiar la aplicación desde el builder
COPY --from=builder /app /app

# Configurar permisos y directorios
RUN mkdir -p /app/var /run/nginx && \
    chmod 777 -R /app/var && \
    chown -R ${user}:${group} /app /run/nginx

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
