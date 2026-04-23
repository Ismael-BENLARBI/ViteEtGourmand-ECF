FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libssl-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
