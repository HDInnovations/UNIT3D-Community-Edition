FROM php:7-fpm
RUN apt-get update
RUN apt-get install -y iputils-ping
RUN ping mariadb
RUN apt-get update && apt-get install -y \
        libzip-dev \
        libsodium-dev \
        libicu-dev \
        unzip \
        libmariadb-dev-compat libmariadb-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure sodium \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip sodium bcmath intl pdo_mysql

WORKDIR /app
RUN apt update && apt install wget -y
COPY artisan .
COPY app/ app
COPY bootstrap/ bootstrap
COPY config/ config
COPY database/ database
COPY routes/ routes
COPY storage/ storage
COPY resources/views/ resources/views
COPY public/index.php .
COPY preload.php .
COPY .env .
COPY composer.json composer.lock composer-setup.sh ./
COPY docker/docker_setup.sh .
RUN ./docker_setup.sh
