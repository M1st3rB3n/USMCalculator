# Documentation Docker - USM Calculator

Cette documentation explique comment construire et utiliser l'image Docker pour l'application USM Calculator.

## Description
L'image Docker fournit un environnement complet pour faire fonctionner l'application USM Calculator. Elle inclut :
- Ubuntu comme système d'exploitation de base.
- PHP 8.4 avec les extensions nécessaires.
- Nginx comme serveur web.
- SQLite pour la base de données.
- Composer pour la gestion des dépendances PHP.

## Prérequis
- Docker installé sur votre machine.

## Construction de l'image
Pour construire l'image localement, placez-vous à la racine du projet et exécutez la commande suivante :

```bash
docker build -t usm-calculator .
```

## Utilisation

### Lancement simple
Pour lancer le conteneur sur le port 8080 de votre machine :

```bash
docker run -p 8080:80 usm-calculator
```
L'application sera alors accessible à l'adresse `http://localhost:8080`.

### Paramétrage de l'administrateur
Au premier démarrage (ou si l'utilisateur n'existe pas), vous pouvez créer automatiquement un compte administrateur en utilisant des variables d'environnement.

```bash
docker run -p 8080:80 \
  -e ADMIN_EMAIL=admin@example.com \
  -e ADMIN_PASSWORD=mon_mot_de_passe_securise \
  usm-calculator
```

| Variable | Description | Par défaut |
| :--- | :--- | :--- |
| `ADMIN_EMAIL` | Email du compte administrateur à créer au démarrage. | (Vide) |
| `ADMIN_PASSWORD` | Mot de passe du compte administrateur. | (Vide) |

## Détails techniques

### Ports
- Le conteneur expose le port **80**.

### Persistance des données
L'application utilise une base de données SQLite située dans le répertoire `var/`. Pour conserver vos données lors de la suppression du conteneur, il est recommandé de monter un volume sur le dossier `var/`.

```bash
docker run -p 8080:80 -v usm_data:/var/www/USMCalculator/var usm-calculator
```

### Script d'entrée (`docker-entrypoint.sh`)
L'image utilise un script d'entrée qui effectue les actions suivantes au démarrage :
1. S'assure que les permissions sur le répertoire `var/` sont correctes.
2. Crée l'utilisateur spécifié par `ADMIN_EMAIL` s'il n'existe pas déjà.
3. Démarre le service PHP-FPM 8.4.
4. Démarre Nginx en arrière-plan.
