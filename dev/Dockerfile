FROM php:8.1-apache

# From the official documentation on https://hub.docker.com/_/php
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# See https://github.com/docker-library/php/issues/391
RUN docker-php-ext-install mysqli
