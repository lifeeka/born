#!/bin/sh
php-fpm7.3 &
service nginx start &
tail -f /var/log/nginx/access.log
