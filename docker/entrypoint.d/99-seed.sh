#!/bin/sh

php artisan db:seed
php artisan filament:optimize
php artisan storage:link
