#!/bin/sh
. /etc/profile.d/umask.sh

case "$RUN_ENVIRONMENT" in
    production) 
        /usr/bin/start
    ;;
    *) 
        composer install
        npm install
        /usr/bin/start
    ;;
esac