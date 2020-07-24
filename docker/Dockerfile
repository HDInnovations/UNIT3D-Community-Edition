FROM php:7-fpm
RUN apt-get update && apt-get install -y \
        libzip-dev \
        libsodium-dev \
        libicu-dev \
        unzip \
        libmariadb-dev-compat libmariadb-dev \
        mariadb-client \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure sodium \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip sodium bcmath intl pdo_mysql
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
WORKDIR /app
RUN apt update && apt install wget -y
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY docker/unit3d.ini /usr/local/etc/php/conf.d/unit3d.ini
COPY composer.json composer.lock composer-setup.sh ./
COPY . .
COPY .env .
COPY public/mix-manifest.json public/mix-manifest.json
COPY docker/docker_setup.sh .
