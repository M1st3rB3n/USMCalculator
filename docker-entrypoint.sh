#!/bin/bash
set -e

# S'assurer que les répertoires de cache et de logs sont accessibles
chmod -R 777 var/

# Création de la base de données si elle n'existe pas et exécution des migrations
if [ -d "var/data" ]; then
    echo "Préparation de la base de données..."
    php bin/console doctrine:database:create --if-not-exists --no-interaction
    echo "Exécution des migrations..."
    php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

    # S'assurer que le fichier de base de données appartient à l'utilisateur qui fait tourner PHP-FPM (souvent www-data ou root ici)
    # et qu'il est accessible en écriture
    chmod -R 777 var/data
fi

# Création de l'utilisateur admin si les variables ENV sont présentes
if [ ! -z "$ADMIN_EMAIL" ] && [ ! -z "$ADMIN_PASSWORD" ]; then
    echo "Tentative de création de l'utilisateur $ADMIN_EMAIL..."
    php bin/console app:create-user "$ADMIN_EMAIL" "$ADMIN_PASSWORD" --no-interaction || echo "Erreur lors de la création de l'utilisateur."
fi

# Lancement des services
service php8.4-fpm start
nginx -g 'daemon off;'
