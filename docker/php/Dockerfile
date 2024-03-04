FROM php:8.1-fpm

RUN apt-get update && apt-get install -y git \
    && docker-php-ext-install pdo pdo_mysql

COPY ./././ /var/www/nginx/books-api
WORKDIR /var/www/nginx/books-api

RUN chown www-data:www-data /var/www/nginx/books-api/books.csv
