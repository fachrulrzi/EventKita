#!/bin/bash

echo "Running Railway deployment tasks..."

# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment tasks completed!"
