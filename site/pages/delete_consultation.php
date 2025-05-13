<?php
const ACCESS_ALLOWED = true;
require_once './config.php';
require_once './functions.php';

if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] !== 'medical_staff') || !isset($_POST['consultation_id'])) {
    header("Location: index.php");
    exit;
}

// Récupérer l'ID de la consultation à supprimer
$consultation_id = intval($_POST['consultation_id']);

// Vérifier si la consultation existe
$stmt = $pdo->prepare("SELECT * FROM consultation WHERE ID = :id");
$stmt->bindValue(':id', $consultation_id, PDO::PARAM_INT);
$stmt->execute();
$consultation = $stmt->fetch();
if (!$consultation) {
    header("Location: consultation.php?error=Consultation%20non%20trouvée");
    exit;
}
// Vérifier si l'utilisateur a le droit de supprimer cette consultation
if ($consultation['medical_staff_id'] !== $_SESSION['ID']) {
    header("Location: consultation.php?error=Vous%20n'avez%20pas%20le%20droit%20de%20supprimer%20cette%20consultation");
    exit;
}
// Supprimer la consultation
$stmt = $pdo->prepare("DELETE FROM consultation WHERE ID = :id");
$stmt->bindValue(':id', $consultation_id, PDO::PARAM_INT);
$stmt->execute();

sendMessage($pdo, 0, $_SESSION['user_type'], $consultation['patient_id'], "patient", "Bonjour, votre consultation du {$consultation['date'] } a été supprimée.");

// Rediriger vers la page de consultation avec un message de succès
header("Location: consultation.php?success=Consultation%20supprimée%20avec%20succès");