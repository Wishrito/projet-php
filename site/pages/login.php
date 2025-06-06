<?php
const ACCESS_ALLOWED = true;
require_once './config.php'; // Inclure la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];
    $request = "";
    $table = ($user_type === 'medical_staff') ? 'medical_staff' : 'patient';
    $select_fields = "id, email, username, password, first_connection, '$table' AS user_type";
    if ($table === 'medical_staff') {
        $select_fields .= ", job, service";
    }

    $request = "SELECT $select_fields FROM $table WHERE username = :usr";
    ?>
<script>
    console.log("<?= $request; ?>");
</script><?php
    // Requête préparée pour sélectionner l'utilisateur
    $requete = $pdo->prepare($request);
        $requete->bindParam(":usr", $username);
        $requete->execute();
    $user = $requete->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, création de la session utilisateur
        $_SESSION['username'] = $user['username'];
        $_SESSION['ID'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        if ($user['user_type'] == "medical_staff") {
            $_SESSION['job'] = $user['job'];
            $_SESSION['service'] = $user['service'];
        }
            if (boolval($user['first_connection']) !== true) {
                header("Location: ./index.php");
            } else {
                header("Location: ./edit_account.php?first_login=true");
            }
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

    <title><?= $site->siteName() ?> - Connexion</title>
</head>

<body>
    <?php if (!isset($_POST['error'])) {
        if (!isset($_SESSION['username'])) { ?>
        <div class="block">
            <form action="" method="post" class="box">
                <h1 class="title is-4 has-text-centered">Connexion</h1>
                <div class="field">
                    <label class="label" for="username">Nom d'utilisateur</label>
                    <div class="control">
                        <input class="input" type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" title="Première lettre du prénom + Nom en minuscule." required>
                    </div>
                </div>

                <div class="field">
                    <label class="label" for="password">Mot de passe</label>
                    <div class="control">
                        <input class="input" type="password" id="password" name="password"
                            placeholder="Entrez votre mot de passe" required    >
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
            <h1 class="title is-4">Bienvenue, <?= $_SESSION['email']; ?></h1>
            <form action="logout.php" method="post">
                <button type="submit" class="button is-danger">Se déconnecter</button>
            </form>
        </div>
    <?php }
    } elseif ($_POST['error'] == 'account_suspended') { ?>
    <div class="notification is-danger">
        Votre compte a été suspendu. Veuillez contacter l'administrateur.
    </div><?php }
    ?>
</body>

<?php include_once './modules/footer.php'; ?>

</html>
