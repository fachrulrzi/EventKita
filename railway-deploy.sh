#!/bin/bash

echo "🚀 Running Railway deployment tasks..."

# Install composer dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches
echo "🔄 Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment tasks completed!"
