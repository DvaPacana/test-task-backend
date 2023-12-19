FROM php:8.1.0-fpm

RUN set -eux; \
    apt-get update && apt-get install -y \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    libxrender1 \
    libpq-dev \
    libfontconfig-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mysqli pdo_mysql zip exif bcmath pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /home/www-data
RUN usermod -u 1000 -d /home/www-data --shell /bin/bash www-data
RUN chown -R www-data:www-data /home/www-data

USER www-data
