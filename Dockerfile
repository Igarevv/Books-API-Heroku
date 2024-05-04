FROM nginx:latest

RUN rm /etc/nginx/conf.d/default.conf

COPY /docker/nginx/books-api.conf /etc/nginx/conf.d/books-api.conf

COPY . /var/www/nginx/books-api

