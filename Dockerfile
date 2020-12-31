FROM composer AS composer

ENV COMPOSER_MEMORY_LIMIT '-1'
RUN apk --no-cache add shadow && usermod -a -G www-data root
WORKDIR /var/www
ADD ./.git/config ./.git/config
ADD ./composer.* ./
RUN composer install --no-autoloader --ignore-platform-reqs

FROM node:14.7-alpine3.11 AS node
WORKDIR /var/www
ADD package.json .
RUN npm install

FROM php:7.4-fpm-alpine3.12

# ENV & Build ARGS
ENV LARAVEL_DIR /var/www
ENV APACHE_LOG_DIR ${LARAVEL_DIR}/storage/logs
WORKDIR ${LARAVEL_DIR}

# Install Deps
RUN apk add --no-cache nginx nodejs npm git openssh-client libzip-dev $PHPIZE_DEPS \
    # install xDebug
    && pecl install xdebug-2.8.1 \
    && pecl install -o -f redis \
    && pecl clear-cache \
    && rm -rf /tmp/pear

# install modules
RUN docker-php-ext-enable redis
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-install zip

# Configure NGINX
RUN rm /etc/nginx/conf.d/default.conf \
    && touch /etc/nginx/conf.d/default.conf \
    && echo 'server {' >> /etc/nginx/conf.d/default.conf \
    && echo '    listen         80 default_server;' >> /etc/nginx/conf.d/default.conf \
    && echo '    listen         [::]:80 default_server;' >> /etc/nginx/conf.d/default.conf \
    && echo '    root           /var/www/public;' >> /etc/nginx/conf.d/default.conf \
    && echo '    index          index.php;' >> /etc/nginx/conf.d/default.conf \
    && echo '  location ~* \.php$ {' >> /etc/nginx/conf.d/default.conf \
    && echo '    fastcgi_pass unix:/tmp/php-fpm.sock;' >> /etc/nginx/conf.d/default.conf \
    && echo '    include         fastcgi_params;' >> /etc/nginx/conf.d/default.conf \
    && echo '    fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;' >> /etc/nginx/conf.d/default.conf \
    && echo '    fastcgi_param   SCRIPT_NAME        $fastcgi_script_name;' >> /etc/nginx/conf.d/default.conf \
    && echo '  }' >> /etc/nginx/conf.d/default.conf \
    && echo '  location / {' >> /etc/nginx/conf.d/default.conf \
    && echo '    try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/conf.d/default.conf \
    && echo '  }' >> /etc/nginx/conf.d/default.conf \
    && echo '}' >> /etc/nginx/conf.d/default.conf \
    # Log to output
    && ln -sf /dev/stderr /var/log/nginx/error.log \
    && sed -i 's/user nginx/user root/g' /etc/nginx/nginx.conf

#Configure xDebug
RUN echo '[XDebug]' >> $PHP_INI_DIR/php.ini-development \
    && echo 'xdebug.remote_enable = 1' >> $PHP_INI_DIR/php.ini-development \
    && echo 'xdebug.remote_autostart = 1' >> $PHP_INI_DIR/php.ini-development \
    && echo 'xdebug.remote_host = host.docker.internal' >> $PHP_INI_DIR/php.ini-development \
    && echo 'xdebug.remote_port = 9000' >> $PHP_INI_DIR/php.ini-development \
    && rm /usr/local/etc/php-fpm.d/zz-docker.conf \
    && touch /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo '[global]' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'daemonize = no' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo '[www]' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'listen.owner = root' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'listen.group = root' >> /usr/local/etc/php-fpm.d/zz-docker.conf \
    && echo 'listen = /tmp/php-fpm.sock' >> /usr/local/etc/php-fpm.d/zz-docker.conf

ADD DockerHelpers/umask.sh /etc/profile.d/umask.sh
RUN chmod +x,o+x,g+x /etc/profile.d/umask.sh

#create Run
ADD DockerHelpers/start.sh /usr/bin/start
RUN chmod +x,o+x,g+x /usr/bin/start

#create Run
ADD DockerHelpers/entry.sh /usr/bin/entry
RUN chmod +x,o+x,g+x /usr/bin/entry

#override real node and php
RUN mv /usr/local/bin/php /usr/local/bin/php.real
RUN mv /usr/bin/npm /usr/bin/npm.real
ADD DockerHelpers/php.sh /usr/local/bin/php
ADD DockerHelpers/npm.sh /usr/bin/npm
RUN chmod +x,o+x,g+x /usr/bin/npm
RUN chmod +x,o+x,g+x /usr/local/bin/php


COPY --from=composer /usr/bin/composer /usr/bin/composer.real
ADD DockerHelpers/composer.sh /usr/bin/composer
RUN chmod +x,o+x,g+x /usr/bin/composer

STOPSIGNAL SIGTERM
EXPOSE 80
EXPOSE 8443

ENTRYPOINT  ["/usr/bin/entry"]
CMD  ["/usr/bin/entry"]

ARG RUN_ENVIRONMENT=development
ENV RUN_ENVIRONMENT=$RUN_ENVIRONMENT
RUN apk add icu-dev
RUN apk add mysql-client
RUN apk add supervisor
RUN docker-php-ext-install pdo_mysql \
&& docker-php-ext-install intl \
&& docker-php-ext-install bcmath \
&& npm install -g cross-env \
&& npm install -g laravel-echo-server

ADD DockerHelpers/unit3d.conf /etc/supervisor/conf.d/unit3d.conf

COPY --from=composer ${LARAVEL_DIR}/vendor ${LARAVEL_DIR}/vendor
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=node ${LARAVEL_DIR}/node_modules ${LARAVEL_DIR}/node_modules
COPY --from=node ${LARAVEL_DIR}/package-lock.json ${LARAVEL_DIR}/package-lock.json

ADD . .

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/memory_limit = 128M/memory_limit = -1/g' $PHP_INI_DIR/php.ini

# Run Node
RUN npm run ${RUN_ENVIRONMENT}
# Create Composer AutoLoad
RUN composer dump-autoload
