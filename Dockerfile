FROM php:8.1.4-fpm-alpine3.14
WORKDIR /var/www/html/image
RUN apk update && apk add git zlib-dev libpng-dev libjpeg-turbo libjpeg-turbo-dev jpeg-dev libwebp-dev freetype-dev -f
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install sockets pcntl mysqli pdo_mysql gd
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --filename=composer --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"