#!/bin/bash

cd /var/www/html
#composer install
#composer dump-autoload
#php artisan --env=local migrate:fresh
#php artisan db:seed
#php artisan db:seed --class=ScenarioSeeder
php-fpm -D
php artisan --env=local queue:listen
/bin/bash
