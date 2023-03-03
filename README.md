# leboncoin-php


## implémentation à faire afin d'améliorer le framework :
  + Changement des fichiers de config php en YAML
  + Enregistrement des services sans utiliser une fonction statique
  + Des nouveaux repository manager pour gérer d’autres sources de données? (XML, CSV, fichier texte…)
  + Ajout d’un service pour envoyer des mails
  + Ajout d’un service pour générer des documents PDF
  + Gestions de divers événements
  + Laisser le choix au développeur entre l’utilisation de templates twig ou bien des fichiers php basiques, pour les vues
  + Ajout d’un ORM (object relational mapper) pour gérer toute la partie repository SQL? (par exemple, doctrine)
 

## Objectif du projet :
Réaliser une application web immitant leboncoin.fr grâce au framework que l'on a créé préalablement.
Le site devra permettre aux utilisateurs : 
  + de s'authenfier
  + de s'inscrire
  + de visualiser / exporter en format pdf des annonces
  + rechercher des annonces par catégorie / nom de l'annonce / localisation

Pour les utilisateurs authentifiés :
  + ajouter une annonce aux favoris
  + rédiger une annonce en tant que brouillon
  + poster une annonce
  + modifier une annonce
  + supprimer une annonce postée 
  + accéder à l'historique des annonces postées
 
Pour les administrateurs :
  + ajouter / supprimer / modifier / une annonce à la place d'un utilisateur
  + supprimer un utilisateur
  + modifier les données personnelles d'un utilisateur
  
Lorsque qu'un utilisateur se sera inscrit, un email lui sera envoyé afin qu'il confirme son inscription.

## Diagramme de classe :

![image](https://user-images.githubusercontent.com/31575276/221962968-657337ff-3df3-4d47-bb15-4d4dfe6ed213.png)


## Groupe : 
Ce projet est réalisé par 
 + Matthias GOUPIL *(https://github.com/matthias-goupil)*
 + Mehdi SAHARI *(https://github.com/Mehdi-Shr)*

## Trello : 
Lien du Trello : *(https://trello.com/b/XbkCKmW0/php-le-mauvais-coin)*
