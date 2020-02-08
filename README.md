# webotek

Cinquième et dernier projet du parcours Développeur Web Junior d'OpenClassrooms (France).

- Librairie en ligne avec une interface de recherche dynamique de livres via l'API Google Books,
  réalisée en JavaScript et la blibliothèque JQuery
- Un système de forum pour pouvoir converser sur différents sujets, avec la possibilité d'en créer.

Ceci est une base solide développée avec symfony 5, avec les possibilités de créer un forum et d'y participer en laissant un message, si l'on est connecté.

Etapes à suivre pour développer sur ce projet :

 - Créer votre répertoire de travail (exemple : webOtek) avec symfony 5 : https://symfony.com/doc/current/setup.html
 - Se placer sur votre répertoire via la console de commandes
 - Cloner le projet via git clone : https://github.com/lutinmaviou/webotek.git
 - git pull
 - Une fois le projet installé : règler le fichier .env pour définir vos identifiants de base de données
 - Faire une migration : php bin/console make:migration
 - Générer la base de données actuelle : php bin/console doctrine:migrations:migrate
 - Faire un symfony serveur:start (http://127.0.0.1:8000/)

Let's build together!

