<?php
include_once '../config.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête préparée pour sélectionner l'utilisateur
    $requete = $pdo->prepare("SELECT id, email, first_name, password FROM users WHERE email = ?");
    $requete->execute([$email]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, création de la session utilisateur
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
    } else { ?>
        <div class='notification is-danger'>Email ou mot de passe incorrect.</div>
    <?php }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title><?php echo $site->siteName() ?> - Compte</title>
</head>

<body>
    <?php if (!isset($_SESSION['email'])) { ?>
        <form action="" method="post">
            <label for="email">nom d'utilisateur:</label>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Login">
            <br>
            <p>Pas encore inscrit? <a href="signup.php">Créer un compte</a></p>
        </form>
    <?php } else { ?>
        <div class="container">
            <h1>Bienvenue <?php echo $_SESSION['email']; ?></h1>
            <form action="logout.php" method="post">
                <input type="submit" value="Logout">
            </form>
        </div>
    <?php } ?>
</body>