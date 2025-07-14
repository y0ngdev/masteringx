#!/bin/bash

set -e


echo "Adding PHP 8.3 repository..."
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

echo "Installing PHP 8.3 and extensions..."
sudo apt install -y php8.3 php8.3-cli php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-mysql php8.3-bcmath php8.3-zip php8.3-fileinfo \
    php8.3-intl php8.3-gd php8.3-sqlite3 php8.3-common php8.3-fpm

echo "Updating php.ini settings..."
PHP_CLI_INI="/etc/php/8.3/cli/php.ini"
PHP_FPM_INI="/etc/php/8.3/fpm/php.ini"

for INI_FILE in "$PHP_CLI_INI" "$PHP_FPM_INI"; do
    if [ -f "$INI_FILE" ]; then
        sudo sed -i "s/^upload_max_filesize = .*/upload_max_filesize =3072M/" "$INI_FILE"
        sudo sed -i "s/^post_max_size = .*/post_max_size =  4096M/" "$INI_FILE"
        sudo sed -i "s/^max_execution_time = .*/max_execution_time = 600/" "$INI_FILE"
        sudo sed -i "s/^max_input_vars = .*/max_input_vars = 3000/" "$INI_FILE"
    fi
done

sudo systemctl reload php8.3-fpm


echo " Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
