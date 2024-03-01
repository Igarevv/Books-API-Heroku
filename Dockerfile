FROM nginx:latest

RUN rm /etc/nginx/conf.d/default.conf
COPY /Docker/nginx/books-api.conf /etc/nginx/conf.d/books-api.conf

COPY . /var/www/books-api