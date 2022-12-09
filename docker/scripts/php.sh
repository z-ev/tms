#!/usr/bin/env bash

composer install \
&& composer run-script post-create-project-cmd \
&& chmod -R 777 bootstrap/ storage/ vendor/

until [[ $(nc -z $MYSQL_HOST 3306 &> /dev/null; echo $?) == '0' ]]
do
    echo -e "wait: $MYSQL_HOST:3306";
    sleep 5
done
php artisan migrate
php artisan l5-swagger:generate

cp /usr/share/zoneinfo/Europe/Moscow /etc/localtime

chown dev:dev -R $PROJECT_PATCH
supervisord
php-fpm
