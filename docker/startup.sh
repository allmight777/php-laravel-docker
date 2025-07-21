#!/bin/bash

echo "📦 Composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "🔄 Laravel migrations..."
php artisan migrate --force

echo "🚀 Lancement de l’application..."
exec /start.sh
