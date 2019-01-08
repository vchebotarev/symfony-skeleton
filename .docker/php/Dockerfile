
FROM php:7.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils

RUN docker-php-ext-install \
        pdo_mysql

# Extension "gd"
RUN apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype-dir=/usr/include/ \
      --with-png-dir=/usr/include/ \
      --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-enable gd

# Extension "redis"
RUN pecl install redis \
    && docker-php-ext-enable redis \
