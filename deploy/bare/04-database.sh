#!/bin/bash

set -e

echo ""
echo "Database Setup"

read -p "Enter your database name [masteringx]: " db_name
db_name=${db_name:-masteringx}

read -p "Enter your database user [root]: " db_user
db_user=${db_user:-root}

read -sp "Enter your database password (leave blank if none): " db_pass
echo ""

read -p "Enter MySQL port [3306]: " db_port
db_port=${db_port:-3306}




echo "Updating .env with database credentials..."
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$db_name|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$db_user|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$db_pass|" .env
sed -i "s|^DB_PORT=.*|DB_PORT=$db_port|" .env


echo ""
echo " Creating the database (if it doesn't exist)..."

if mysql -u"$db_user" -p"$db_pass" -e "USE \`$db_name\`;" 2>/dev/null; then
    echo " Database '$db_name' already exists."
else
    echo "Creating database '$db_name'..."
    mysql -u"$db_user" -p"$db_pass" -e "CREATE DATABASE \`$db_name\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" && \
    echo " Database created successfully." || {
        echo " Failed to create database. Please verify your MySQL user/password ot host and try again."
        exit 1
    }
fi


echo "Migrating database..."
php artisan migrate --seed  --force
php artisan db:seed

