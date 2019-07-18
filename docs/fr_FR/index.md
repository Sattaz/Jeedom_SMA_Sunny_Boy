Description 
===

Le plugin SMA-SunnyBoy permet de récupérer les informations de production photovoltaïque des onduleurs SMA de type Sunny Boy et Sunny Tripower.

En application courante, il permet par exemple d'allumer ou d'éteindre un équipement en fonction de la puissance réelle générée par votre installation PV depuis un scénario dans Jeedom.

Configuration du plugin 
===

La configuration du plugin est très simple.
Une fois installé, il suffit de créer un nouvel équipement et de le configurer de la manière suivantes:

![SunnyBoy](https://sattaz.github.io/Jeedom_SMA_Sunny_Boy/pictures/SMA_SunnyBoy_2.jpg)

Comme pour chaque plugin Jeedom, il faudra indiquer le 'Nom de l'équipement', un 'Objet parent' et une 'Catégorie'.
Ne pas oublier de cocher les cases 'Activer' et 'Visible'.

Puis viennent aussi quelques paramètres dédiés aux spécification de l'onduleur SMA:

-   IP de l'onduleur : veuillez renseigner l'adresse IP de l'interface 'WebConnect' de l'onduleur.

-   Port de l'onduleur : veuillez renseigner le port de l'interface 'WebConnect' de l'onduleur. (443 pour connexion HTTPS, 80 pour connexion HTTP ... à essayer selon la configuration de l'onduleur)

-   Mot de passe : veuillez renseigner le mot de passe que utiliser pour se connecter à l'interface 'WebConnect' de l'onduleur.

-   Puissance crête : veuillez renseigner la puissance de votre installation photovoltaïque (en watts)

-> Veuillez dès à présent appuyer sur le bouton 'Sauvegarder' afin d'enregistrer la configuration.
-> Cette action va automatiquement créer les commandes de l'équipement.

Commandes de l'équipement 
===

Comme énoncé dans le précédent chapitre, les commandes de l'équipement sont automatiquement crées dès lors que la configuration est sauvegardée.

IMPORTANT : ne pas effacer la commande 'Session ID' car elle est automatiquement créée et utilisée pour se connecter à l'onduleur.

![SunnyBoy](https://sattaz.github.io/Jeedom_SMA_Sunny_Boy/pictures/SMA_SunnyBoy_3.jpg)



Le widget 
===

Le widget arrive comme montré sur la photo ci-après et la jauge indiquant la valeur 'PV Production' est calibrée (min/max) par la puissance crête indiquée dans la configuration de l'équipement.

![SunnyBoy](https://sattaz.github.io/Jeedom_SMA_Sunny_Boy/pictures/SMA_SunnyBoy_1.jpg)

Libre à vous de modifier le widget afin de l'adapter à votre style de présentation.



Autres informations 
===

* Le plugin rafraîchi les données toutes les minutes.
* Vous pouvez créer plusieurs équipements pour gérer les onduleurs d'une ferme photovoltaïque.
