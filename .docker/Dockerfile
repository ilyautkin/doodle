FROM php:7.4-fpm

RUN apt-get update -y
RUN apt-get install -y \
            libbz2-dev \
            libxml2-dev \
            git \
            libzip-dev \
            libc-client-dev \
            libkrb5-dev \
            libpng-dev \
            libjpeg-dev \
            libwebp-dev \
            libfreetype6-dev \
            libkrb5-dev \
            libicu-dev \
            zlib1g-dev \
            zip \
            ffmpeg \
            libmemcached11 \
            libmemcachedutil2 \
            build-essential \
            libmemcached-dev \
            gnupg2 \
            libpq-dev \
            libpq5 \
            libz-dev \
            webp

RUN docker-php-ext-configure gd \
    --with-webp=/usr/include/ \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install \
    gd \
    bz2 \
    mysqli \
    opcache \
    pdo_mysql \
    soap \
    zip \
    exif \
    fileinfo

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

USER 1000
