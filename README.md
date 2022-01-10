# Web-Boissons

Web-Boissons est un site permettant de rechercher grâce à une liste d'aliments des recettes de boissons et de pouvoir les enregistrer pour pouvoir les consulter ultérieurement.

Lien du site : http://boissons-flo.atwebpages.com/

## Instruction d'installation

 - Lancez la commande `$ composer install` dans le dossier `/symfony` afin d’installer des dépendances
- Créez une base vide 
- Paramétrez la connexion à la base : variable `DATABASE_URL` dans le fichier `/symfony/.env` (`DATABASE_URL="mysql://username:pwd@url:port/db_name"`) 
- Installez les tables avec le fichier `database.sql`
- Peuplez les tables avec les données du fichier Donnees.inc.php en accédant à la route `/import/AD85FC347812422E` (Il est conseillé de modifier la clé pour des raison de sécurité, modifiable dans le Contrôleur `/symfony/src/Controller/HomeController.php`) 
- Voila le site est maintenant fonctionnel 

Pour lancer le site lancez la commande `$ php -S localhost:8000 -t public_html/`

## Infos
Ce projet a été développé dans le cadre d'un projet de [la Faculté des Sciences et Technologies de Nancy](https://fst.univ-lorraine.fr)