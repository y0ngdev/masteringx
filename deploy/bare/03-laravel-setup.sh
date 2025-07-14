#!/bin/bash

set -e


echo "Setting file permissions..."
sudo chown -R $USER:www-data .
    chmod -R ug+rwx storage bootstrap/cache


echo "Setting up the environment config..."
if [ ! -f .env ]; then
    cp .env.example .env

else
    echo ".env file already exists. Skipping creation."
fi

echo " Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader


echo "Installing and building frontend assets..."
npm install
npm run build



php artisan key:generate
php artisan optimize
php artisan storage:link



