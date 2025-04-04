<?php
const ACCESS_ALLOWED = true;
include_once './config.php';


if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}
$user_type = $_SESSION['user_type'];
$request = "";
switch ($user_type) {
    case 'patient':
        $request = "SELECT * FROM patient WHERE ID = ?";
    case 'medical_staff':
        $request = "SELECT * FROM medical_staff WHERE ID = ?";
    }

$requete = $pdo->prepare($request);
$requete->execute([$_SESSION ["ID"]]);
$user = $requete->fetch(PDO::FETCH_ASSOC);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site->siteName() ?> - Compte</title>
</head>
<body>
    <div class="block">
        <h1 class="title is-4 has-text-centered">Mon compte</h1>
        <div class="content">
            <p><strong>Identifiant:</strong> <?php echo $user['ID'] ?></p>
            <p><strong>Nom d'utilisateur:</strong> <?php echo $user['username'] ?></p>
            <p><strong>Mot de Passe:</strong> <?php echo $user['password'] ?></p>
            <p><strong>Email:</strong> <?php echo $user['email'] ?></p>
            <p><strong>Prénom:</strong> <?php echo $user['first_name'] ?></p>
            <p><strong>Nom:</strong> <?php echo $user['last_name'] ?></p>
            <p><strong>Date de naissance:</strong> <?php echo $user['birth_date'] ?></p>
            <p><strong>Date d'admission:</strong> <?php echo $user['admission_date'] ?></p>
            <p><strong>Date de sortie:</strong> <?php echo $user['leaving_date'] ?></p>
            <p><strong>Etage:</strong> <?php echo $user['floor_lvl'] ?></p>
        </div>
    </div>
    <a href="logout.php" class="button is-danger">Déconnexion</a>
</body>

<footer class="footer">
     <div>
        <?php echo $_SESSION['ID'] ?>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>