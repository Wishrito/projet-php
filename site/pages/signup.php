<?php
const ACCESS_ALLOWED = true;
require './config.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_type = $_POST['user_type'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $username = strtolower($firstname[0]) . strtolower(str_replace(' ', '-', $lastname));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe

    // Vérifier si l'utilisateur existe déjà
    $requete = $pdo->prepare("SELECT COUNT(*) FROM ? WHERE email = ?");
    $requete->execute([$user_type, $username]);
    $count = $requete->fetchColumn();

    if ($count > 0) { ?>
        <div class='notification is-danger'>Cet email est déjà utilisé.</div>
        <?php
    } else {
        // Insérer l'utilisateur dans la base de données
        $requete = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($requete->execute([$user_id, $username, $password])) {
            header("Location: ../index.php");
            exit();
        } else {
            ?>
            <div class='notification is-danger'>Erreur lors de l'inscription.</div>
            <?php
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site->siteName() ?> - Inscription</title>
</head>

<body>
    <section class="section">
        <div class="block">
            <form action="" method="post" class="box">
                <h1 class="title is-4 has-text-centered">Inscription</h1>
            <form method="POST" action="">
                <div>
                    <div class="field">
                        <label class="label">Nom</label>
                        <div class="control">
                            <input class="input" type="text" name="lastname" placeholder="Entrez votre nom d'utilisateur"
                                required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Prénom</label>
                        <div class="control">
                            <input class="input" type="text" name="firstname" placeholder="Entrez votre nom d'utilisateur"
                                required>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" type="email" name="email" placeholder="Entrez votre email" required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="password" placeholder="Entrez votre mot de passe"
                            required>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Confirmer le mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" name="passwordConfirm"
                            placeholder="Entrez votre mot de passe" required>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <label for="user_type">Type d'utilisateur</label>
                        <select name="user_type" id="">
                            <option value="medical_staff">Corps Médical</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary">S'inscrire</button>
                    </div>
                </div>

            </form>
        </div>
    </section>
</body>

<footer>
     <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>