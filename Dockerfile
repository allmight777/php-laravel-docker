FROM richarvey/nginx-php-fpm:3.1.6

# Copier les fichiers de l'application
COPY . .

# Laravel et PHP config
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# ⚠️ NE PAS sauter composer install
# Supprimer cette ligne ou mettre à 0 (0 = ne pas sauter)
ENV SKIP_COMPOSER 0

# Ajouter le script de démarrage personnalisé
COPY docker/startup.sh /startup.sh
RUN chmod +x /startup.sh

# Utiliser le script custom qui lance les migrations puis démarre normalement
CMD ["/startup.sh"]
