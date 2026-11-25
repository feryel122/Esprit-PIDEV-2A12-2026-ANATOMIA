ANATOMIA — module Forum et Utilisateurs

1. Copier le dossier du projet dans C:\xampp\htdocs\
   (le dossier qui contient UserAndForum).
2. Démarrer Apache et MySQL dans XAMPP.
3. Ouvrir phpMyAdmin et importer UserAndForum/database.sql.
   Si la base existe déjà, importer plutôt UserAndForum/migration.sql
   une seule fois. Les ALTER déjà appliqués peuvent afficher une erreur :
   dans ce cas, ne pas réimporter.
4. Vérifier UserAndForum/config.php : localhost, anatomia_forum, root, mot de passe vide.
5. Ouvrir http://localhost/Esprit-PIDEV-2A12-2026-ANATOMIA-forum-community-hosting/UserAndForum/View/Frontoffice/accueil.php
   Adaptez le nom du dossier s'il est différent.
6. Créer un compte depuis ajoutuser.php (utilisateur ou médecin).
7. Premier administrateur, dans phpMyAdmin :
   UPDATE users SET role='admin' WHERE email='votre-email@example.com';
   Le formulaire public ne peut pas créer un administrateur.
8. Back-office : View/Backoffice/adminlte/dashboard.php
