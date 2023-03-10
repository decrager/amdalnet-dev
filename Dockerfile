# Set the base image for subsequent instructions
FROM php:8.1-fpm

WORKDIR /var/www

# Update packages

# RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - \
    # && apt-get update \
RUN apt-get update \
    && apt-get install -y nodejs npm netcat graphviz libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev libonig-dev libzip-dev libpq-dev git libcurl4-openssl-dev \
    && apt-get clean

# Install extensions
RUN docker-php-ext-install mbstring zip exif pcntl pdo pgsql pdo_pgsql curl
# RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-configure pgsql -with-pgsql 
RUN docker-php-ext-install gd
RUN yes "no" | pecl install redis && docker-php-ext-enable redis


# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# COPY . .
# COPY .env.example .env

# CMD ["bash", "./laravue-entrypoint.sh"]

