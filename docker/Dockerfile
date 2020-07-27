FROM php:7-fpm
RUN apt-get update && apt-get install -y \
        libzip-dev \
        libsodium-dev \
        libicu-dev \
        unzip \
        libmariadb-dev-compat libmariadb-dev \
        mariadb-client \
        libcurl4-openssl-dev libssl-dev \
    && docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) zip bcmath intl pdo_mysql curl
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis
VOLUME ["/app/static", "/app/files", "/app/storage", "/app/vendor"]
WORKDIR /app
RUN apt update && apt install wget -y
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY docker/unit3d.ini /usr/local/etc/php/conf.d/unit3d.ini
COPY composer.json composer.lock composer-setup.sh preload.php ./
COPY docker/docker_setup.sh .
COPY grumphp.yml .
RUN mkdir -p public
COPY ./artisan .
COPY ./app/ app/
COPY ./bootstrap/ bootstrap/
COPY ./database/ database/
COPY ./config/ config/
COPY ./routes/ routes/
COPY ./resources/ resources/
COPY ./docker/env .env
RUN ./composer-setup.sh
RUN php -d memory_limit=-1 composer global require hirak/prestissimo

# COPY ./public/mix-sri.json ./static/mix-sri.json

