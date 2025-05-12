<?php
const ACCESS_ALLOWED = true;
require_once './config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medical_staff') {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=crm_hopital', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['consultation_id'];
    $debrief = $_POST['debrief'];

    $stmt = $pdo->prepare("UPDATE consultation SET debrief = ? WHERE ID = ?");
    $stmt->execute([$debrief, $id]);

    header("Location: consultation.php?success=Débrief%20modifié%20avec%20succès");
    exit;
}

if (!isset($_GET['id'])) {
    die("Aucune consultation spécifiée.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM consultation WHERE ID = ?");
$stmt->execute([$id]);
$consultation = $stmt->fetch();

if (!$consultation) {
    die("Consultation introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le débrief</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <form class="form-modif" method="POST" action="modifier_consultation.php">
        <h2>Modifier le débrief de la consultation</h2>
        <input type="hidden" name="consultation_id" value="<?= htmlspecialchars($consultation['ID']) ?>">
        <label for="debrief">Débrief :</label>
        <textarea name="debrief"><?= htmlspecialchars($consultation['debrief']) ?></textarea>
        <button type="submit">Enregistrer</button>
    </form>

</body>
</html>
