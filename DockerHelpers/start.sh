#!/bin/sh
. /etc/profile.d/umask.sh

if test -f "$PHP_INI_DIR/php.ini-$RUN_ENVIRONMENT"; then
    mv -f $PHP_INI_DIR/php.ini-$RUN_ENVIRONMENT $PHP_INI_DIR/php.ini
fi


nginx -g 'pid /tmp/nginx.pid; daemon off;' & php-fpm