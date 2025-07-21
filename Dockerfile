FROM richarvey/nginx-php-fpm:3.1.6

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet Laravel dans le conteneur
COPY . /var/www/html

# Config Laravel et PHP
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV SKIP_COMPOSER 0

# Ajouter le script de démarrage personnalisé
COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh

# Utiliser le script custom qui lance les migrations puis démarre nginx/php-fpm
CMD ["/startup.sh"]
