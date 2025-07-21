FROM richarvey/nginx-php-fpm:3.1.6

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# Variables d'environnement Laravel & PHP
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV SKIP_COMPOSER 0

# Installer dompdf (barryvdh/laravel-dompdf)
RUN composer require barryvdh/laravel-dompdf

# Ajouter le script de démarrage personnalisé
COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh

# Lancer le script personnalisé au démarrage
CMD ["/startup.sh"]
