# projet-php

## Installation des dépendances et initialisation de la base de données

### Initialisation de la base de données

1. Assurez-vous que votre serveur MySQL est en cours d'exécution.
2. Importez le fichier `projet-php.sql` dans votre base de données. Voici les étapes pour le faire via Laragon :

   1. Ouvrez Laragon et assurez-vous que le serveur MySQL est en cours d'exécution.
   2. Cliquez sur le bouton **Database** dans l'interface principale de Laragon. Cela ouvrira l'outil de gestion de base de données (par exemple, HeidiSQL).
   3. Connectez-vous à votre serveur MySQL en utilisant vos identifiants (par défaut, l'utilisateur est `root` et le mot de passe est vide, sauf si vous l'avez modifié).
   4. Importez le fichier `projet-php.sql` en utilisant l'option **Import** ou en exécutant directement le fichier SQL via l'interface de l'outil.
   5. Une fois l'importation terminée, vérifiez que les tables et données ont été correctement ajoutées à la base.

   Votre base de données est maintenant prête à être utilisée avec le projet.

Une fois ces étapes terminées, le projet devrait être prêt à fonctionner.

## explications sur les fichiers présents

- fichier `.gitattributes`. ce fichier est juste un fichier système créé à chaque création de dépôt Github. je ne m'en suis jamais servi personellement
- fichier `.gitignore`. ce fichier permet de spécifier à github les dossiers ou fichiers que l'on ne souhaite pas partager lors de l'envoi de fichiers vers le dépot
- fichier `README.` C'est ici
- fichier `projet-php.sql`. Ce fichier contient la requête de création de la base, et contiendra aussi les requêtes pour son remplissage
- dossier `site`. le dossier contient le site et tous les fichiers nécessaires à son fonctionnement. l'arborescence est organisée de sorte à facilement retrouver les fichers

### identifiants utilisateur

#### **Table "medical_staff"**

| Nom d'utilisateur | Prénom    | Nom de famille | Mot de passe sécurisé      |
| ----------------- | ---------- | -------------- | ---------------------------- |
| admin             | *(vide)* | *(vide)*     | `LeM0t.2P4sS3DeuXL!adM1N,` |
| ssystème         | santéplus | système       | `Sy$teme2025!a`            |
| jhocdé           | Julien     | Hocdé         | `Jul!3n_HocdE45`           |
| mmoreau           | Marie      | Moreau         | `M@r13More@u#55`           |
| ykhan             | Youssef    | Khan           | `Y0uss3f_K#han99`          |
| cbernard          | Camille    | Bernard        | `C@mill3_B3rn@rd_88`       |
| afernandez        | Anaïs     | Fernandez      | `An@1s_Fern@nd3Z+22`       |

#### table "patients"

| Nom d'utilisateur | Prénom | Nom de famille | Mot de passe sécurisé |
| ----------------- | ------- | -------------- | ----------------------- |
| lroth             | Lucie   | Roth           | `Luc!e_Roth*2025`     |
| tdupont           | Thomas  | Dupont         | `Th0m@s_Dup0nt!33`    |
| ldurand           | Léa    | Durand         | `L3@_Dur@nd_2025#`    |
| aroux             | Antoine | Roux           | `AntoineR0ux$!54`     |
| eleroy            | Emma    | Leroy          | `Emma!L3r0y_#88`      |
