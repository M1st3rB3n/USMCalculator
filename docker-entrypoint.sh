#!/bin/bash
set -e

# S'assurer que les répertoires de cache et de logs sont accessibles
chmod -R 777 var/

# Création de l'utilisateur admin si les variables ENV sont présentes
if [ ! -z "$ADMIN_EMAIL" ] && [ ! -z "$ADMIN_PASSWORD" ]; then
    echo "Tentative de création de l'utilisateur $ADMIN_EMAIL..."
    php bin/console app:create-user "$ADMIN_EMAIL" "$ADMIN_PASSWORD" --no-interaction || echo "Erreur lors de la création de l'utilisateur."
fi

# Lancement des services
service php8.4-fpm start
nginx -g 'daemon off;'
