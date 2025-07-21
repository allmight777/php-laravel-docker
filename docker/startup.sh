#!/bin/bash
set -e

echo "✅ Lancement de composer install"
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "✅ Lancement des migrations (avec suppression des tables)"
php artisan migrate:fresh --seed --force

echo "✅ Démarrage du service nginx-php-fpm"
/start.sh
