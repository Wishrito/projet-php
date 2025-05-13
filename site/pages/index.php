<?php
const ACCESS_ALLOWED = true;
require "./config.php";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site->siteName() ?> - Accueil</title>
</head>

<body>

    <main>
        
        <h1>Votre santé, notre priorité</h1>
    </main>

    <footer class="footer">
        <div>
            <p>© 2025 <?= $site->siteName() ?>. Tous droits réservés.</p>
        </div>
    </footer>

</body>

</html>
