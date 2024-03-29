FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

COPY ./app/weather.php /var/www/html/

COPY ./icons/*.png /var/www/html/icons/
COPY ./fonts/*.ttf /var/www/html/

EXPOSE 80
