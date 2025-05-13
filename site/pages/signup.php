<?php
const ACCESS_ALLOWED = true;
require_once './config.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $birth_date = $_POST['birth_date'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];
    $username = strtolower($firstname[0]) . strtolower(str_replace(' ', '-', $lastname));

    // Vérification de la confirmation du mot de passe
    if ($password !== $passwordConfirm) {
        echo "<div class='notification is-danger'>Les mots de passe ne correspondent pas.</div>";
        return;
    }

    // Vérification de la force du mot de passe
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/', $password)) {
        echo "<div class='notification is-danger'>Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</div>";
        return;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hachage du mot de passe

    try {
        // Vérifier si l'utilisateur existe déjà
        $requete = $pdo->prepare("SELECT COUNT(*) FROM $user_type WHERE email = ?");
        $requete->execute([$email]);
        $count = $requete->fetchColumn();
    } catch (PDOException $e) {
        $err = $e->getMessage();
        die("Erreur de connexion : $err");
    }

    if ($count > 0) {
        echo "<div class='notification is-danger'>Cet email est déjà utilisé.</div>";
    } else {
        // Insérer l'utilisateur dans la base de données
        $requete = $pdo->prepare("INSERT INTO $user_type (first_name, last_name, username, email, birth_date, password) VALUES (?, ?, ?, ?, ?, ?)");
        if ($requete->execute([$firstname, $lastname, $username, $email, $birth_date, $passwordHash])) {
            header("Location: ./index.php");
            exit();
        } else {
            echo "<div class='notification is-danger'>Erreur lors de l'inscription.</div>";
        }
    }
}
date_default_timezone_set('Europe/Paris');
$current_date = date('Y-m-d'); // Format YYYY-MM-DD
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site->siteName() ?> - Inscription</title>
</head>

<body>
<section class="section has-background-light" style="margin-top: 4rem;">
    <div class="container box p-5">
        <form method="POST" action="">
            <h1 class="title is-4 has-text-centered mb-5">Inscription</h1>

            <div class="columns is-variable is-6">
                <div class="column">
                    <div class="field">
                        <label for="lastname" class="label">Nom</label>
                        <div class="control">
                            <input class="input" type="text" name="lastname" placeholder="Entrez votre nom" required>
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <label for="firstname" class="label">Prénom</label>
                        <div class="control">
                            <input class="input" type="text" name="firstname" placeholder="Entrez votre prénom" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field">
                <label for="email" class="label">Email</label>
                <div class="control">
                    <input class="input" type="email" name="email" placeholder="Entrez votre email" required
                        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                        title="Format d'email invalide.">
                </div>
            </div>

            <div class="field">
                <label for="birth_date" class="label">Date de naissance</label>
                <div class="control">
                    <input class="input" type="date" name="birth_date" max="<?= $current_date ?>" required>
                </div>
                </div>

            <div class="columns is-variable is-6">
                <div class="column">
                    <div class="field">
                        <label for="password" class="label">Mot de passe</label>
                        <div class="control">
                            <input class="input" type="password" name="password"
                                placeholder="Entrez votre mot de passe"
                                required
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{12,}$">
                        </div>
                    </div>
                </div>

                <div class="column">
                    <div class="field">
                        <label for="passwordConfirm" class="label">Confirmer</label>
                        <div class="control">
                            <input class="input" type="password" name="passwordConfirm"
                                placeholder="Confirmez votre mot de passe"
                                required
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{12,}$">
                        </div>
                    </div>
                </div>
            </div>

            <div class="field">
                <label for="user_type" class="label">Type d'utilisateur</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="user_type">
                            <option value="medical_staff">Corps Médical</option>
                            <option value="patient">Patient</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field mt-5">
                <div class="control has-text-centered">
                    <button type="submit" class="button is-primary is-medium">Inscrire</button>
                </div>
            </div>
        </form>
    </div>
</section>

</body>
<?php include_once './modules/footer.php'; ?>

</html>