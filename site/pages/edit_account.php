<?php
const ACCESS_ALLOWED = true;
include_once './config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['ID'];
$user_type = $_SESSION['user_type'];

if ($user_type === 'patient') {
    $sql = "SELECT * FROM patient WHERE ID = ?";
} elseif ($user_type === 'medical_staff') {
    $sql = "SELECT * FROM medical_staff WHERE ID = ?";
} else {
    die("Type d'utilisateur inconnu.");
}

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';
    $profile_pic_path = $_POST['profile_pic'] ?? $user['profile_pic'];
    $service_id = $_POST['service'] ?? $user['service'];
    $job_id = $_POST['job'] ?? $user['job'];

    // Mise à jour des informations utilisateur
    if (empty($errors)) {
        $update_sql = "UPDATE " . ($user_type === 'patient' ? 'patient' : 'medical_staff') . "
                       SET first_name = ?, last_name = ?, email = ?, birth_date = ?, profile_pic = ?, service = ?," . ($user_type === 'medical_staff' ? " job = ?" : " ") .
            "WHERE ID = ?";
        $stmt = $pdo->prepare($update_sql);
        $params = [$first_name, $last_name, $email, $birth_date, $profile_pic_path, $service_id, $job_id, $user_id];
        if ($user_type !== 'patient') {
            $params[] = $job_id;

        }
        $stmt->execute($params);
        $success = true;
    }

    // Recharger les données mises à jour
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $site->siteName(); ?> - Mon compte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<section class="section">
    <div class="container">
        <div class="box">
            <h1 class="title">Modifier mes informations</h1>

            <?php if (!empty($errors)): ?>
                <div class="notification is-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($success): ?>
                <div class="notification is-success">
                    Modifications enregistrées avec succès.
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="field">
                    <label class="label">Photo de profil</label>
                    <div class="avatar-grid">
                        <?php for ($i = 1; $i <= 10; $i++):
                            $img = "../src/img/profile_pics/Staff_$i.png";
                            $checked = ($user['profile_pic'] === $img) ? 'checked' : '';
                        ?>
                        <label class="avatar-option">
                            <input type="radio" name="profile_pic" value="<?= $img ?>" <?= $checked ?>>
                            <img src="<?= $img ?>" alt="Avatar <?= $i ?>">
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Prénom</label>
                    <input class="input" type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Nom</label>
                    <input class="input" type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <input class="input" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Date de naissance</label>
                    <input class="input" type="date" name="birth_date" value="<?= htmlspecialchars($user['birth_date']) ?>" required>
                </div>

                <div class="field">
                    <label class="label">Service</label>
                    <div class="select">
                        <select name="service" required>
                            <?php
                            $service_sql = "SELECT * FROM service";
                            $service_stmt = $pdo->query($service_sql);
                            while ($service = $service_stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($service['ID'] == $user['service']) ? 'selected' : '';
                                echo "<option value=\"{$service['ID']}\" {$selected}>{$service['libelle']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Poste</label>
                    <div class="select">
                        <select name="job" required>
                            <?php
                            $job_sql = "SELECT * FROM job";
                            $job_stmt = $pdo->query($job_sql);
                            while ($job = $job_stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($job['ID'] == $user['job']) ? 'selected' : '';
                                echo "<option value=\"{$job['ID']}\" {$selected}>{$job['libelle']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <h2 class="subtitle">Changer le mot de passe</h2>

                <div class="field">
                    <label class="label">Mot de passe actuel</label>
                    <input class="input" type="password" name="current_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{12,}$">
                </div>

                <div class="field">
                    <label class="label">Nouveau mot de passe</label>
                    <input class="input" type="password" name="new_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{12,}$">
                </div>

                <div class="field">
                    <label class="label">Confirmer le nouveau mot de passe</label>
                    <input class="input" type="password" name="confirm_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[\s\S]{12,}$">
                </div>

                <div class="buttons mt-4">
                    <button class="button is-success" type="submit">Enregistrer</button>
                    <a href="account.php" class="button is-light">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</section>
</body>

<footer class="footer">
    <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
    </div>
</footer>

</html>
