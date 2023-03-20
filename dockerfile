# Create and build composer dependencies
FROM composer:2.0 AS vendor

COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


# Create and build all node dependencies, creating all required assets
FROM node:14.15 AS assets

COPY . /home/node/app
WORKDIR /home/node/app

RUN npm install \
    && npm install -g gulp gulp-cli \
    && gulp production


# Create and build application
FROM php:8.0-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get install -y \
        libpng-dev \
        zlib1g-dev \
        libxml2-dev \
        libzip-dev \
        libonig-dev \
        zip \
        curl \
        unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-source delete

COPY --from=vendor /usr/bin/composer /usr/bin/composer
COPY --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --from=assets /home/node/app/public/assets/ /var/www/html/public/assets/
COPY . /var/www/html

# Copy the scheduler script into the image
COPY scheduler.sh /usr/local/bin/scheduler

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite headers \
    && chmod u+x /usr/local/bin/scheduler \
    && php artisan migrate \
    && php artisan config:cache