# p8_ToDo_-_Co
Améliorez une application existante de ToDo &amp; Co

<a href="https://app.codacy.com/gh/Herve-Dev/p8_todoandco/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade"><img src="https://app.codacy.com/project/badge/Grade/312c7289008c42d2b9055d8495e46ccc"/></a>

![Image toDo Co](https://github.com/Herve-Dev/p8_ToDo_-_Co/assets/82519929/c04fff58-dedf-44a2-b090-0dbfee5b2497)

# Contexte
Vous venez d’intégrer une startup dont le cœur de métier est une application permettant de gérer ses tâches quotidiennes. L’entreprise vient tout juste d’être montée, et l’application a dû être développée à toute vitesse pour permettre de montrer à de potentiels investisseurs que le concept est viable (on parle de Minimum Viable Product ou MVP).

Le choix du développeur précédent a été d’utiliser le framework PHP Symfony, un framework que vous commencez à bien connaître ! 

Bonne nouvelle ! ToDo & Co a enfin réussi à lever des fonds pour permettre le développement de l’entreprise et surtout de l’application.

Votre rôle ici est donc d’améliorer la qualité de l’application. La qualité est un concept qui englobe bon nombre de sujets : on parle souvent de qualité de code, mais il y a également la qualité perçue par l’utilisateur de l’application ou encore la qualité perçue par les collaborateurs de l’entreprise, et enfin la qualité que vous percevez lorsqu’il vous faut travailler sur le projet.

Ainsi, pour ce dernier projet de spécialisation, vous êtes dans la peau d’un développeur expérimenté en charge des tâches suivantes :

- l’implémentation de nouvelles fonctionnalités ;
- la correction de quelques anomalies ;
- et l’implémentation de tests automatisés.
  
Il vous est également demandé d’analyser le projet grâce à des outils vous permettant d’avoir une vision d’ensemble de la qualité du code et des différents axes de performance de l’application.

Il ne vous est pas demandé de corriger les points remontés par l’audit de qualité de code et de performance. Cela dit, si le temps vous le permet, ToDo & Co sera ravi que vous réduisiez la dette technique de cette application.

# Description du besoin 
Corrections d'anomalies <br>

Une tâche doit être attachée à un utilisateur
Actuellement, lorsqu’une tâche est créée, elle n’est pas rattachée à un utilisateur. Il vous est demandé d’apporter les corrections nécessaires afin qu’automatiquement, à la sauvegarde de la tâche, l’utilisateur authentifié soit rattaché à la tâche nouvellement créée.

Lors de la modification de la tâche, l’auteur ne peut pas être modifié.

Pour les tâches déjà créées, il faut qu’elles soient rattachées à un utilisateur “anonyme”.

Choisir un rôle pour un utilisateur
Lors de la création d’un utilisateur, il doit être possible de choisir un rôle pour celui-ci. Les rôles listés sont les suivants :

- rôle utilisateur (ROLE_USER) ;
- rôle administrateur (ROLE_ADMIN).
Lors de la modification d’un utilisateur, il est également possible de changer le rôle d’un utilisateur.

Implémentation de nouvelles fonctionnalités
Autorisation
Seuls les utilisateurs ayant le rôle administrateur (ROLE_ADMIN) doivent pouvoir accéder aux pages de gestion des utilisateurs.

Les tâches ne peuvent être supprimées que par les utilisateurs ayant créé les tâches en question.

Les tâches rattachées à l’utilisateur “anonyme” peuvent être supprimées uniquement par les utilisateurs ayant le rôle administrateur (ROLE_ADMIN).

# Installation
1.  Faite un git clone du projet
2.  Ouvrez votre terminal dans le projet et faite un `composer install`.
3.  Créez votre un fichier `.env.local` et configurez votre base de données (Vous trouverez un exemple dans le fichier `.env`).
4.  Ouvrez un nouveau terminal et taper `php bin/console doctrine:database:create`.
5.  Taper egalement a la suite `php bin/console doctrine:migrations:migrate`.
6.  Il faut ajouter les datafixtures avec cette ligne à la suite toujours dans le terminal `php bin/console doctrine:fixtures:load --no-interaction`.
7.  Une derniere ligne dans le terminal `Symfony server:start` pour lancer le serveur.
8.  Vous êtes encore là ? c'est parfait le projet est installé avec succès !

# Test Unit/Functional 
1.  Créez votre un fichier `.env.test.local` et configurez votre base de données (Vous trouverez un exemple dans le fichier `.env.test`).
2.  Il ya un MakeFile la commande est `make test` vous lancer cette commande dans votre terminal apres configuration du fichier .env.test.local
