#!/bin/bash

set -e

echo "Deploying..."

php artisan down


INSTALL_SCRIPT="./deploy/bare/install.sh"

if [ ! -f .env ] || [ ! -d vendor ]; then
  echo "Running install.sh from deploy/bare/..."
  bash "$INSTALL_SCRIPT"
else
  echo "App is already installed. Skipping install."
fi



php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optional: restart queue workers
php artisan queue:restart

echo "App deployed!"
