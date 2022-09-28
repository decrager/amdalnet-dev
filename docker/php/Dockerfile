FROM php:8

WORKDIR /var/www

RUN apt-get update && \
  apt-get install -y libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev libonig-dev libzip-dev libpq-dev libcurl4-openssl-dev && \
  apt-get clean

# Install extensions
RUN docker-php-ext-install mbstring zip exif pcntl pdo pgsql pdo_pgsql curl
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure pgsql -with-pgsql 
RUN docker-php-ext-install gd

COPY ./storage/logs/laravel.log storage/logs/laravel.log

CMD ["tail",  "-f",  "/dev/null"]
# CMD ["bash", "./laravue-entrypoint.sh"]