FROM php:8.3-fpm

#Installer les extensions PHP requises pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    openssl \
    nano \
    apt-utils \
    lsb-release \
    gnupg \
    git \
    && rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions http-stable fileinfo ctype iconv intl opcache pdo_mysql zip mbstring

#Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copier le script d'entrée personnalisé
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Rendre le script exécutable
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposez le port 80 pour les requêtes HTTP
EXPOSE 80

# Empêche les soucis d'installation de dépendances composer en spécifiant à git que le dossier /var/www/html est "safe"
# même si il n'est pas détenu par le même utilisateur
RUN git config --global --add safe.directory /var/www/html

# Définir l'utilisateur pour éviter d'exécuter en root
RUN chown -R 1000:1000 /var/www/html

# On exécute le script entrypoint.sh
CMD ["/usr/local/bin/entrypoint.sh"]


