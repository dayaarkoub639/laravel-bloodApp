# Etapes a faire apres avoir cloner le projet

## Step 01 : A verifier
- Lancer Xampp
- Verifier votre version de PHP , Il faut une version >= PHP 8.2
- Verifier Laravel et composer versions
## Step 02 : Modifier le fichier .env

- Créer la base de données pour ce projet dans phpmyadmin
- Mettre le nom de cette Base de données dans le .env
- Importer les tables wilayas et communes dans SQL


## Les commandes (Via Command Line)

- composer update
- php artisan key:generate
- php artisan migrate
- php artisan db:seed 
- php artisan storage:link 


### Pour faire fonctionner le pusher (boostsrap.js)
 - npm install
 - npm run build

### Lancer le serveur 
- php artisan serve
php artisan queue:work   

### Effacer le cache
- php artisan config:clear
- php artisan optimize:clear


 

 

 
