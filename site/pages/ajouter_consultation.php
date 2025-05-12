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
    // Récupérer les données du formulaire
    $patient_id = $_POST['patient_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];  // La date sélectionnée
    $hour = $_POST['hour'];   // L'heure sélectionnée
    $minute = $_POST['minute'];  // La minute sélectionnée
    $second = $_POST['second'];  // La seconde sélectionnée
    $debrief = $_POST['debrief'];
    $medical_staff_id = $_SESSION['ID'];

    // Construire la date complète au format 'YYYY-MM-DD HH:MM:SS'
    $date_time = "$date $hour:$minute:$second";

    // Convertir la date et l'heure en format 'YYYY-MM-DD HH:MM:SS' (format MySQL DATETIME)
    $datetime = date('Y-m-d H:i:s', strtotime($date_time));



    // Ajouter la consultation à la base de données
    $insert_query = "INSERT INTO consultation (patient_id, service_id, medical_staff_id, debrief, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$patient_id, $service_id, $medical_staff_id, $debrief, $datetime]);

    // Redirection avec message de succès
    header("Location: consultation.php?success=Consultation%20ajoutée%20avec%20succès");
    exit;
}
?>
