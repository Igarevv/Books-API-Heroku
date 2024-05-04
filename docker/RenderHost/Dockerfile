FROM nginx:latest

RUN rm /etc/nginx/conf.d/default.conf

COPY ././nginx/books-api.conf /etc/nginx/conf.d/books-api.conf

COPY ./././ /var/www/nginx/books-api

FROM php:8.1-fpm

RUN apt-get update && apt-get install -y git \
    && docker-php-ext-install pdo pdo_mysql

COPY ./././ /var/www/nginx/books-api
WORKDIR /var/www/nginx/books-api

EXPOSE 9000

RUN chown www-data:www-data /var/www/nginx/books-api/books.csv

FROM mysql:latest

COPY ./././database/dump.sql /docker-entrypoint-initdb.d/dump.sql

ENV MYSQL_ROOT_PASSWORD=0375Ihor_
ENV MYSQL_PASSWORD=Devbase75_
ENV MYSQL_USER=dev
ENV MYSQL_DATABASE=BookAPI