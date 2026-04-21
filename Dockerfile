# Utilise l'image de base que vous aviez
FROM richarvey/nginx-php-fpm:3.1.6

# INSTALLATION DE NODE.JS ET NPM (Indispensable pour Vite/Tailwind)
RUN apk add --no-cache nodejs npm

COPY . .

# INSTALLATION DES DÉPENDANCES JS ET COMPILATION (Vite)
# Cette étape crée le fichier manifest.json qui manque actuellement
RUN npm install && npm run build

# Configuration de l'image Docker (Vos réglages d'origine)
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Configuration Laravel
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Correction des permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]