#!/bin/bash

echo "✅ Lancement de composer install"
composer install

echo "✅ Lancement des migrations"
php artisan migrate --force

echo "✅ Démarrage du conteneur"
/start.sh  # script original de l'image richarvey/nginx-php-fpm
