<?php
const ACCESS_ALLOWED = true;
require_once './config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

$request = "SELECT * FROM medical_staff WHERE ID = :id";
$stmt = $pdo->prepare($request);
$stmt->bindParam(':id', $_SESSION['ID'], PDO::PARAM_INT);
$stmt->execute();
$medical_staff = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$medical_staff || $medical_staff['job'] != 75) {
    header('Location: index.php');
}

// Requête préparée pour sélectionner tous les patients
$patients_request = "SELECT email, username, first_name, last_name, profile_pic FROM patient";
$medical_staff_request = "SELECT
    ms.email,
    ms.username,
    ms.first_name,
    ms.last_name,
    ms.profile_pic,
    j.libelle AS job_title
FROM
    medical_staff ms
JOIN
    job j ON ms.job = j.id;
";

$stmt = $pdo->prepare($patients_request);
$stmt->execute();
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare($medical_staff_request);
$stmt->execute();
$medical_staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site->siteName() ?> - Panneau d'administration</title>
</head>
<body>
    <section class="section">
        <div class="block">
            <h1 class="title is-4 has-text-centered">Liste des utilisateurs</h1>
            <div class="columns is-multiline">
                <?php foreach ($patients as $patient) { ?>
                    <div class="column is-one-quarter">
                        <div class="box">
                            <figure class="image is-128x128">
                                <img src="<?php echo $patient['profile_pic'] ?>" alt="Photo de profil de <?php echo $patient['first_name'] . ' ' . $patient['last_name'] ?>">
                            </figure>
                            <h2 class="title is-5"><?php echo $patient['first_name'] . ' ' . $patient['last_name'] ?></h2>
                            <p><?php echo $patient['email'] ?></p>
                            <p><?php echo $patient['username'] ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <h1 class="title is-4 has-text-centered">Liste du personnel médical</h1>
            <div class="columns is-multiline">
                <?php foreach ($medical_staffs as $staff) { ?>
                    <div class="column is-one-quarter">
                        <div class="box">
                            <figure class="image is-128x128">
                                <img src="<?php echo $staff['profile_pic'] ?>" alt="Photo de profil de <?php echo $staff['first_name'] . ' ' . $staff['last_name'] ?>">
                            </figure>
                            <h2 class="title is-5"><?php echo $staff['first_name'] . ' ' . $staff['last_name'] ?></h2>
                            <p><?php echo $staff['email'] ?></p>
                            <p><?php echo $staff['username'] ?></p>
                            <p><?php echo $staff['job_title'] ?></p>
                        </div>
                    </div>
                <?php } ?>
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