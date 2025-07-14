#!/bin/bash

set -e

echo "Updating and installing core packages..."
sudo apt update && sudo apt upgrade -y

sudo apt install -y software-properties-common apt-utils \
    libpq-dev libpng-dev openssl libjpeg-dev libfreetype6-dev \
    libwebp-dev libxpm-dev libgd-dev libzip-dev libicu-dev \
    zip unzip git libonig-dev libxml2-dev libsqlite3-dev \
    libssl-dev curl

echo " Installing MySQL Server..."
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

sudo apt install supervisor -y

PROJECT_DIR=$(readlink -f "$(dirname "$0")/../..")
mkdir -p "$PROJECT_DIR/storage/logs"

sudo cp "$PROJECT_DIR/deploy/supervisor/laravel-worker.conf" /etc/supervisor/conf.d/laravel-worker.conf

sudo sed -i "s|{{PROJECT_PATH}}|$PROJECT_DIR|g" /etc/supervisor/conf.d/laravel-worker.conf

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*

echo " Installing FFmpeg..."
sudo apt install -y ffmpeg

echo " Installing Node.js 22.x..."
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
