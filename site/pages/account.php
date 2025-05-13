<?php
const ACCESS_ALLOWED = true;
include_once './config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

$user_type = $_SESSION['user_type'];
$request = "";

if ($user_type === 'patient') {
    $request = "SELECT * FROM patient WHERE ID = ?";
} elseif ($user_type === 'medical_staff') {
    $request = "SELECT ms.*, s.libelle as service_name, j.libelle as job_name
                FROM medical_staff ms
                LEFT JOIN service s ON ms.service = s.ID
                LEFT JOIN job j ON ms.job = j.ID
                WHERE ms.ID = ?";
} else {
    // Garde-fou au cas où
    die("Type d'utilisateur inconnu.");
}

$requete = $pdo->prepare($request);
$requete->execute([$_SESSION['ID']]);
$user = $requete->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $site->siteName(); ?> - Mon compte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section class="section">
        <div class="container">
            <div class="box has-text-centered">
                <figure class="image is-128x128 is-inline-block mb-4">
                    <img class="is-rounded" src="<?= htmlspecialchars($user['profile_pic'] ?? 'img/profile_pics/default.png'); ?>"
                        alt="Photo de profil">
                </figure>
                <h1 class="title is-4"></strong> <?= $user['username'] ?? 'N/A'; ?></h1>
                <div class="content has-text-left is-size-5 mt-5">
                    <p><strong>Email:</strong> <?= $user['email']; ?></p>
                    <p><strong>Prénom:</strong> <?= $user['first_name']; ?></p>
                    <p><strong>Nom:</strong> <?= $user['last_name']; ?></p>
                    <p><strong>Date de naissance:</strong> <?= $user['birth_date']; ?></p>

                    <?php if ($user_type === 'patient'): ?>
                                <p><strong>Date d'admission:</strong> <?= $user['admission_date']; ?></p>
                    <?php elseif ($user_type === 'medical_staff'): ?>
                                <p><strong>Date d'embauche:</strong> <?= $user['hiring_date']; ?></p>
                            <p><strong>Service:</strong> <?= $user['service_name']; ?></p>
                            <p><strong>Poste:</strong> <?= $user['job_name']; ?></p>
                    <?php endif; ?>
                </div>

                <div class="buttons is-centered mt-4">
                    <a href="edit_account.php" class="button is-info">Modifier mes informations</a>
                    <a href="logout.php" class="button is-danger">Déconnexion</a>
                </div>
            </div>
        </div>
    </section>
</body>

<?php include_once './modules/footer.php'; ?>

</html>
