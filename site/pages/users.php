<?php
const ACCESS_ALLOWED = true;
require_once './config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

$request = "SELECT ID, email, username, first_name, last_name, profile_pic, is_suspended, job FROM medical_staff WHERE ID = :id";
$stmt = $pdo->prepare($request);
$stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
$stmt->execute();
$medical_staff = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$medical_staff || $medical_staff['job'] != 75) {
    header('Location: index.php');
}

// Requête préparée pour sélectionner tous les patients
$patients_request = "SELECT ID, email, username, first_name, last_name, profile_pic, is_suspended FROM patient";
$medical_staff_request = "SELECT
    ms.ID,
    ms.email,
    ms.username,
    ms.first_name,
    ms.last_name,
    ms.profile_pic,
    ms.is_suspended,
    j.libelle AS job_title
    
FROM
    medical_staff ms
JOIN
    job j ON ms.job = j.id;
";

$stmt = $pdo->prepare($patients_request);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare($medical_staff_request);
$stmt->execute();
$medical_staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site->siteName() ?> - Panneau d'administration</title>
</head>
<body>
    <section class="section">
        <div class="block">
            <h1 class="title is-4 has-text-centered">Liste des utilisateurs</h1>
            <div class="columns is-multiline">
                <?php foreach ($users as $user) { ?>
                    <div class="column is-one-quarter">
                        <div class="box">
                            <figure class="profile-picture">
                                <img src="<?= $user['profile_pic'] ?>"
                                    alt="Photo de profil de <?= $user['first_name'] . ' ' . $user['last_name'] ?>">
                            </figure>
                            <h2 class="title is-5"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h2>
                            <p><?= $user['email'] ?></p>
                            <p><?= $user['username'] ?></p>
                            <div>
                                <?php $is_suspended = $user['is_suspended']; ?>
                                <form action="suspend_user.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $user['ID'] ?>">
                                    <input type="hidden" name="user_type" value="patient">
                                    <input type="hidden" name="state" value="<?= $is_suspended ?>">
                                    <button type="submit"
                                        class="button is-danger is-small"><?= boolval($is_suspended) === false ? "Suspendre" : "Rétablir" ?></button>
                                </form>
                                <form action="edit_password.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $user['ID'] ?>">
                                    <input type="hidden" name="user_type" value="patient">
                                    <div>
                                        <input type="password" name="new-password">
                                        <button type="submit" class="button is-info is-small">Changer le mot de passe</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <h1 class="title is-4 has-text-centered">Liste du personnel médical</h1>
            <div class="columns is-multiline">
                <?php foreach ($medical_staffs as $staff) { ?>
                    <div class="column is-one-quarter">
                        <div class="box">

                            <figure class="profile-picture">
                                <img src="<?= $staff['profile_pic'] ?>"
        alt="Photo de profil de <?= $staff['first_name'] . ' ' . $staff['last_name'] ?>">
                            </figure>
                            <h2 class="title is-5"><?= $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
                            <p><?= $staff['email'] ?></p>
                            <p><?= $staff['username'] ?></p>
                            <p><?= $staff['job_title'] ?></p>
                            <div>
                                <?php $is_suspended = $staff['is_suspended']; ?>
                                <form action="suspend_user.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $staff['ID'] ?>">
                                    <input type="hidden" name="user_type" value="medical_staff">
                                    <input type="hidden" name="state" value="<?= $is_suspended ?>">
                                    <button type="submit"
                                        class="button is-danger is-small"><?= boolval($is_suspended) === false ? "Suspendre" : "Rétablir" ?></button>
                                </form>
                                <form action="edit_password.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?= $staff['ID'] ?>">
                                    <input type="hidden" name="user_type" value="medical_staff">
                                    <div>
                                        <input type="password" name="new-password">
                                        <button type="submit" required class="button is-info is-small">Changer le mot de passe</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</body>
<?php include_once './modules/footer.php'; ?>

</html>