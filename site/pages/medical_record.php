<?php
const ACCESS_ALLOWED = true;
require_once './config.php';

if (!isset($_SESSION['ID']) || !isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['ID'];

// Gestion des ajouts ou modifications (uniquement pour le personnel médical)
if ($user_type === 'medical_staff' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = $_POST['diagnosis'];
    $notes = $_POST['notes'];
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = $user_id;
    $record_id = isset($_POST['record_id']) ? intval($_POST['record_id']) : 0;

    if ($record_id > 0) {
        // Modification
        $stmt = $pdo->prepare("UPDATE medical_record SET diagnosis = ?, notes = ?, record_date = CURRENT_TIMESTAMP WHERE id = ? AND doctor_id = ?");
        $stmt->execute([$diagnosis, $notes, $record_id, $doctor_id]);
    } else {
        // Création
        $stmt = $pdo->prepare("INSERT INTO medical_record (diagnosis, notes, doctor_id, patient_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$diagnosis, $notes, $doctor_id, $patient_id]);
    }

    header("Location: medical_record.php");
    exit();
}

// Récupération des dossiers à afficher
if ($user_type === 'patient') {
    $stmt = $pdo->prepare("SELECT * FROM medical_record WHERE patient_id = ?");
    $stmt->execute([$user_id]);
} elseif ($user_type === 'medical_staff') {
    $stmt = $pdo->prepare("
        SELECT mr.*, p.first_name, p.last_name
        FROM medical_record mr
        JOIN patient p ON mr.patient_id = p.ID
        WHERE mr.doctor_id = ?
        ORDER BY mr.record_date DESC
    ");
    $stmt->execute([$user_id]);
    $patients_stmt = $pdo->query("SELECT ID, first_name, last_name FROM patient");
    $patients = $patients_stmt->fetchAll(PDO::FETCH_ASSOC);
}

$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dossiers Médicaux - <?php echo $site->siteName(); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<section class="section">


        <?php if ($user_type === 'medical_staff'): ?>
            <h2 class="subtitle">Créer un nouveau dossier</h2>
            <form method="post" class="box">
                <div class="field">
                    <label class="label">Patient</label>
                    <div class="control">
                        <div class="select">
                            <select name="patient_id" required>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?php echo $patient['ID']; ?>">
                                        <?php echo htmlspecialchars($patient['first_name'] . " " . $patient['last_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Diagnostic</label>
                    <div class="control">
                        <textarea name="diagnosis" class="textarea" required></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Notes</label>
                    <div class="control">
                        <textarea name="notes" class="textarea"></textarea>
                    </div>
                </div>

                <div class="control">
                    <button type="submit" class="button is-primary">Enregistrer</button>
                </div>
            </form>
        <?php endif; ?>

              <div class="container">
              <?php if ($_SESSION['user_type'] === 'patient') : ?>
              <h1 class="title is-4">Mon dossier médical</h1>
          <?php else : ?>
              <h1 class="title is-4">Dossiers Médicaux</h1>
              <h2 class="subtitle is-5">Liste des dossiers</h2>
          <?php endif; ?>

        <table class="table is-fullwidth is-striped">
            <thead>
            <tr>
                <?php if ($user_type === 'medical_staff') echo '<th>Patient</th>'; ?>
                <th>Date</th>
                <th>Diagnostic</th>
                <th>Notes</th>
                <?php if ($user_type === 'medical_staff') echo '<th>Actions</th>'; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $record): ?>
                <tr>
                    <?php if ($user_type === 'medical_staff') {
                        echo '<td>' . htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) . '</td>';
                    } ?>
                    <td><?php echo htmlspecialchars($record['record_date']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($record['diagnosis'])); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($record['notes'])); ?></td>
                    <?php if ($user_type === 'medical_staff' && $record['doctor_id'] == $user_id): ?>
                        <td>
                            <details>
                                <summary class="button is-small is-info is-light">Modifier</summary>
                                <form method="post" class="mt-2">
                                    <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                    <input type="hidden" name="patient_id" value="<?php echo $record['patient_id']; ?>">

                                    <div class="field">
                                        <textarea name="diagnosis" class="textarea is-small" required><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>
                                    </div>
                                    <div class="field">
                                        <textarea name="notes" class="textarea is-small"><?php echo htmlspecialchars($record['notes']); ?></textarea>
                                    </div>
                                    <div class="control">
                                        <button type="submit" class="button is-success is-light is-small">Enregistrer</button>
                                    </div>
                                </form>
                            </details>
                        </td>
                    <?php elseif ($user_type === 'medical_staff'): ?>
                        <td><em>Non modifiable</em></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
</body>

<footer class="footer">
     <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>
