FROM php:8.1-fpm

RUN apt-get update && apt-get install -y git \
    && docker-php-ext-install pdo pdo_mysql

COPY ./././ /var/www/books-api
WORKDIR /var/www/books-api
