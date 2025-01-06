<?php
const ACCESS_ALLOWED = true;
require './config.php'; // Inclure la connexion à la base de données


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    $request = "";
    switch ($user_type) {
        case 'patient':
            $request = "SELECT id, email, first_name, password FROM user_type WHERE email = '?'";
        case 'medical_staff':
            $request = "SELECT id, email, first_name, password FROM medical_staff WHERE email = '?'";
    }
    // Requête préparée pour sélectionner l'utilisateur
    $requete = $pdo->prepare($request);
    $requete->execute([$email]);
    $user = $requete->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, création de la session utilisateur
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
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

    <title><?php echo $site->siteName() ?> - Connexion</title>
</head>

<body>
    <?php if (!isset($_SESSION['email'])) { ?>
        <div class="block">
            <form action="" method="post" class="box">
                <h1 class="title is-4 has-text-centered">Connexion</h1>
        
                <div class="field">
                    <label class="label" for="email">Nom d'utilisateur</label>
                    <div class="control">
                        <input class="input" type="text" id="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                </div>
        
                <div class="field">
                    <label class="label" for="password">Mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" id="password" name="password"
                            placeholder="Entrez votre mot de passe" required>
                    </div>
                </div>
        
                <div class="field">
                    <label class="label" for="user_type">Type d'utilisateur</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="user_type" id="user_type">
                                <option value="medical_staff">Corps médical</option>
                                <option value="patient">Patient</option>
                            </select>
                        </div>
                    </div>
                </div>
        
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary is-fullwidth">Se connecter</button>
                    </div>
                </div>
        
                <p class="help is-info has-text-centered">Pas encore inscrit ? Contactez l'administrateur informatique.</p>
            </form>
        </div>
    <?php } else { ?>
        <div class="container">
            <h1 class="title is-4">Bienvenue, <?php echo $_SESSION['email']; ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" class="button is-danger">Se déconnecter</button>
            </form>
        </div>
    <?php } ?>
</body>

