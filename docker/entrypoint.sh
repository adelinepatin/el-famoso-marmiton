#!/bin/bash
set -e
basePath=/var/www/html

# On force un composer install pour installer les dépendances manquantes + un composer update pour mettre à jour celles
# qui existent déjà
composer install --no-interaction --no-scripts --no-plugins --prefer-dist --optimize-autoloader --no-progress --ignore-platform-reqs
composer update --ignore-platform-reqs

#On effectue les migrations doctrine
if [ "$(ls -A $basePath/migrations/*.php)" ]; then
    php bin/console doctrine:migrations:migrate --no-interaction
else
    echo "Le dossier migrations est vide, aucune migration à exécuter."
fi

# On force les droits sur /var pour www-data
chown -R 1000:1000 $basePath
chown -R www-data:www-data $basePath/var

# On execute le service Apache
php-fpm

# Démarrer le service principal
exec "$@"
