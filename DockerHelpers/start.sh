#!/bin/sh
. /etc/profile.d/umask.sh

if test -f "$PHP_INI_DIR/php.ini-$RUN_ENVIRONMENT"; then
    mv -f $PHP_INI_DIR/php.ini-$RUN_ENVIRONMENT $PHP_INI_DIR/php.ini
fi


supervisord -c /etc/supervisord.conf
supervisorctl reload
nginx -g 'pid /tmp/nginx.pid; daemon off;' & php-fpm