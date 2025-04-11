<?php
const ACCESS_ALLOWED = true;
require_once './config.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $username = strtolower($firstname[0]) . strtolower(str_replace(' ', '-', $lastname));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
    try {
        // Vérifier si l'utilisateur existe déjà
        $requete = $pdo->prepare("SELECT COUNT(*) FROM $user_type WHERE email = ?");
        $requete->execute([$email]);
        $count = $requete->fetchColumn();
    } catch (PDOException $e) {
        $err = $e->getMessage();
        die("Erreur de connexion : $err");
    }
    if ($count > 0) { ?>
        <div class='notification is-danger'>Cet email est déjà utilisé.</div>
        <?php
    } else {
        // Insérer l'utilisateur dans la base de données
        $requete = $pdo->prepare("INSERT INTO $user_type (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)");
        if ($requete->execute([$firstname, $lastname, $username, $email, $password])) {
            header("Location: ./index.php");
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
            <form method="POST" action="">
                <div>
                    <h1 class="title is-4 has-text-centered">Inscription</h1>
                    <div class="form-row">
                        <div class="field">
                            <label for="lastname"class="label">Nom</label>
                            <div id="lastname" class="control">
                                <input class="input" type="text" name="lastname" placeholder="Entrez votre nom"
                                    required>
                            </div>
                        </div>

                        <div class="field">
                            <label for="name" class="label">Prénom</label>
                            <div id="name" class="control">
                                <input class="input" type="text" name="firstname" placeholder="Entrez votre prénom"
                                    required>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="field">
                    <label for="mail" class="label">Email</label>
                    <div id="mail" class="control">
                        <input class="input" type="email" name="email" placeholder="Entrez votre email" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Format d'email invalide.">
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="password" class="label">Mot de passe</label>
                        <div id="password" class="control">
                            <input class="input" type="password" name="password" placeholder="Entrez votre mot de passe"
                                required>
                        </div>
                    </div>
    
                    <div class="field">
                        <label for="confirmpassword" class="label">Confirmer</label>
                        <div id="confirmpassword" class="control">
                            <input class="input" type="password" name="passwordConfirm"
                                placeholder="Entrez votre mot de passe" required>
                        </div>
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

<footer class="footer">
    <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
    </div>
</footer>

</html>