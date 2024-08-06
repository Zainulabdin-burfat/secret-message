FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git curl nano procps libpng-dev libonig-dev libxml2-dev libjpeg-dev libzip-dev zip unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --enable-gd --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# XDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Supervisor
RUN apt-get update && apt-get -y install supervisor
COPY docker/app/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# DB client
RUN apt-get -y install mariadb-client

# npm
RUN curl -sL https://deb.nodesource.com/setup_18.x  | bash -
RUN apt-get -y install nodejs
