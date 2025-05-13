<?php
const ACCESS_ALLOWED = true;
require_once './config.php'; // Connexion à la base de données

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=crm_hopital', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
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
    <title><?php echo $site->siteName() ?> - Consultations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<div class="container">
    <h1>Consultations</h1>

    <?php if (isset($_GET['success'])): ?>
    <div class="success-message">
        <?= htmlspecialchars($_GET['success']) ?>
    </div>
    <?php endif; ?>


    <div class="consultation-columns">
        <div class="consultation-block">
            <h2>Consultations passées</h2>
            <?php if (count($past_consultations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th><?php echo $user_type === 'patient' ? 'Médecin' : 'Patient'; ?></th>
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
                                    <td colspan="4" style="text-align:right;">
                                        <a href="modify_consultation.php?id=<?= $c['ID'] ?>" class="btn-modif">Modifier le débrief</a>
                                    </td>


                            <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune consultation passée.</p>
            <?php endif; ?>
        </div>

        <div class="consultation-block">
            <h2>Consultations futures</h2>
            <?php if (count($future_consultations) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Service</th>
                            <th><?php echo $user_type === 'patient' ? 'Médecin' : 'Patient'; ?></th>
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
                                            <textarea name="message" placeholder="Envoyer un message au médecin"></textarea>
                                            <button type="submit">Envoyer</button>
                                        </form>
                                    <?php else: ?>
                                                    <form action="delete_consultation.php" method="post">
                                                        <input type="hidden" name="consultation_id" value="<?= $c['ID'] ?>">
                                            <textarea name="debrief" placeholder="Ajouter un débrief"></textarea>
                                            <button type="submit">Ajouter</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune consultation future.</p>
            <?php endif; ?>

            <?php if ($user_type === 'medical_staff'): ?>

                <div class="container">
        <h1>Ajouter une Consultation</h1>

        <form action="add_consultation.php" method="POST">
            <div class="form-group">
                <label for="patient_id">Sélectionner un Patient :</label>
                <select name="patient_id" id="patient_id" required>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?= htmlspecialchars($patient['ID']) ?>">
                            <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service_id">Sélectionner un Service :</label>
                <select name="service_id" id="service_id" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['ID']) ?>">
                            <?= htmlspecialchars($service['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date et heure de la Consultation :</label>
                <input type="date" name="date" id="date" required>
                <input type="time" name="time" id="" min="8:00" max="18h00" required>
            </div>

            <div class="form-group">
                <label for="debrief">Détails :</label>
                <textarea name="debrief" id="debrief" rows="4" required></textarea>
            </div>

            <button type="submit">Ajouter la Consultation</button>
        </form>
    </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>

<footer class="footer">
     <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>
