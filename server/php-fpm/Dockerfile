FROM php:7.2-fpm-buster

RUN apt-get -y update
RUN apt-get -y install curl wget git libmcrypt-dev libpng-dev libwebp-dev libjpeg62-turbo-dev libxpm-dev libfreetype6-dev

RUN docker-php-ext-configure gd --with-gd --with-webp-dir --with-jpeg-dir \
    --with-png-dir --with-zlib-dir --with-xpm-dir --with-freetype-dir
RUN docker-php-ext-install -j$(nproc) opcache mysqli pdo_mysql gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
update-ca-certificates

WORKDIR /var/www
