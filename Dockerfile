FROM php:8.2-apache

RUN apt-get update && apt-get install -y libssl-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN a2enmod rewrite