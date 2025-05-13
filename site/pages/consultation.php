<?php
const ACCESS_ALLOWED = true;
require_once './config.php'; // Connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['ID'];

$query = "";
$params = [];

if ($user_type == "patient") {
    $query = "SELECT c.ID, c.debrief, c.date, s.libelle AS service, m.first_name, m.last_name 
              FROM consultation c 
              LEFT JOIN service s ON c.service_id = s.ID
              LEFT JOIN medical_staff m ON c.medical_staff_id = m.ID
              WHERE c.patient_id = ?";
    $params = [$user_id];
} elseif ($user_type == "medical_staff") {
    $query = "SELECT c.ID, c.debrief, c.date, p.first_name, p.last_name, s.libelle AS service 
              FROM consultation c
              LEFT JOIN patient p ON c.patient_id = p.ID
              LEFT JOIN service s ON c.service_id = s.ID
              WHERE c.medical_staff_id = ?";
    $params = [$user_id];
} else {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$consultations = $stmt->fetchAll();

date_default_timezone_set('Europe/Paris');
$now = date('Y-m-d H:i:s');
$past_consultations = [];
$future_consultations = [];

foreach ($consultations as $c) {
    if ($c['date'] < $now) {
        $past_consultations[] = $c;
    } else {
        $future_consultations[] = $c;
    }
}

// Récupérer tous les patients (nom et prénom)
$patients_query = "SELECT ID, first_name, last_name FROM patient";
$patients_stmt = $pdo->prepare($patients_query);
$patients_stmt->execute();
$patients = $patients_stmt->fetchAll();

// Récupérer tous les services
$services_query = "SELECT ID, libelle FROM service";
$services_stmt = $pdo->prepare($services_query);
$services_stmt->execute();
$services = $services_stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= $site->siteName() ?> - Consultations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="container mt-5">
    <h1 class="title is-3 has-text-centered">Consultations</h1>

    <?php if (isset($_GET['success'])): ?>
                <div class="notification is-success is-light">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
    <?php endif; ?>

    <div class="columns is-multiline">
        <!-- Consultations passées -->
        <div class="column is-6">
            <div class="box">
                <h2 class="title is-5">Consultations passées</h2>
                <?php if (count($past_consultations) > 0): ?>
                    <div class="table-container">
                        <table class="table is-striped is-hoverable is-fullwidth is-size-7">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th><?= $user_type === 'patient' ? 'Médecin' : 'Patient'; ?></th>
                                    <th>Débrief</th>
                                    <?php if ($user_type === 'medical_staff'): ?>
                                        <th>Action</th>
                                    <?php endif; ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($past_consultations as $c): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($c['date']) ?></td>
                                                <td><?= htmlspecialchars($c['service']) ?></td>
                                                <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></td>
                                                <td><?= nl2br(htmlspecialchars($c['debrief'])) ?></td>
                                        <?php if ($user_type === 'medical_staff'): ?>
                                                                    <td>
                                                                        <a href="modify_consultation.php?id=<?= $c['ID'] ?>" class="button is-small is-info is-light">Modifier</a>
                                                        </td>
                                        <?php endif; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                        </table>
                    </div>
                <?php else: ?>
                    <p>Aucune consultation passée.</p>
                <?php endif; ?>
                </div>
        </div>

        <!-- Consultations futures -->
        <div class="column is-6">
            <div class="box">
                <h2 class="title is-5">Consultations futures</h2>
                <?php if (count($future_consultations) > 0): ?>
                    <div class="table-container">
                        <table class="table is-striped is-hoverable is-fullwidth is-size-7">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th><?= $user_type === 'patient' ? 'Médecin' : 'Patient'; ?></th>
                                    <th>Débrief</th>
                                    <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($future_consultations as $c): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($c['date']) ?></td>
                                                <td><?= htmlspecialchars($c['service']) ?></td>
                                                <td><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></td>
                                                <td><?= nl2br(htmlspecialchars($c['debrief'])) ?></td>
                                                <td>
                                                    <?php if ($user_type === 'patient'): ?>
                                                        <form method="POST" action="send_message.php">
                                                            <input type="hidden" name="receiver_id" value="<?= $c['ID'] ?>">
                                                    <input type="hidden" name="receiver_type" value="medical_staff">
                                                    <textarea class="textarea is-small" name="message" placeholder="Message..." rows="2"></textarea>
                                                    <button type="submit" class="button is-small is-link mt-1">Envoyer</button>
                                                </form>
                                            <?php else: ?>
                                                        <form action="delete_consultation.php" method="post">
                                                            <input type="hidden" name="consultation_id" value="<?= $c['ID'] ?>">
                                                    <textarea class="textarea is-small" name="debrief" placeholder="Débrief..." rows="2"></textarea>
                                                    <button type="submit" class="button is-small is-primary mt-1">Ajouter</button>
                                                </form>
                                            <?php endif; ?>
                                                    </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                    </table>
                    </div>
                <?php else: ?>
                    <p>Aucune consultation future.</p>
                <?php endif; ?>

                <!-- Formulaire d'ajout -->
                <?php if ($user_type === 'medical_staff'): ?>
                    <hr>
                    <h3 class="title is-5">Ajouter une Consultation</h3>
                    <form action="add_consultation.php" method="POST">
                        <div class="field">
                            <label class="label">Patient</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="patient_id" required>
                                        <?php foreach ($patients as $patient): ?>
                                            <option value="<?= htmlspecialchars($patient['ID']) ?>">
                                                <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Service</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="service_id" required>
                                        <?php foreach ($services as $service): ?>
                                            <option value="<?= htmlspecialchars($service['ID']) ?>">
                                                <?= htmlspecialchars($service['libelle']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Date & Heure</label>
                            <div class="control">
                                <input class="input is-small mb-2" type="date" name="date" required>
                                <input class="input is-small" type="time" name="time" min="08:00" max="18:00" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Débrief</label>
                            <div class="control">
                                <textarea class="textarea is-small" name="debrief" rows="3" required></textarea>
                            </div>
                        </div>

                        <button type="submit" class="button is-primary is-small">Ajouter</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


</body>

<?php include_once './modules/footer.php'; ?>

</html>
