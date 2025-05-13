<?php
const ACCESS_ALLOWED = true;
require_once './config.php';
require_once './functions.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'medical_staff') {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $patient_id = $_POST['patient_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['date'];  // La date sélectionnée
    $time = $_POST['time'];   // l'heure sélectionnée
    $debrief = $_POST['debrief'];
    $medical_staff_id = $_SESSION['ID'];

    // Construire la date complète au format 'YYYY-MM-DD HH:MM:SS'
    $date_time = "$date $time";

    // Convertir la date et l'heure en format 'YYYY-MM-DD HH:MM:SS' (format MySQL DATETIME)
    $datetime = date('Y-m-d H:i:s', strtotime($date_time));



    // Ajouter la consultation à la base de données
    $insert_query = "INSERT INTO consultation (patient_id, service_id, medical_staff_id, debrief, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($insert_query);
    $stmt->execute([$patient_id, $service_id, $medical_staff_id, $debrief, $datetime]);

    // code de send_message.php
    // Insère le message dans la base de données

    $message = "Bonjour, votre consultation a été ajoutée avec succès. Voici les détails :\n";
    $message .= "Date : " . date('d/m/Y', strtotime($date)) . "\n";
    sendMessage($pdo, 0, $_SESSION['user_type'], $patient_id, "patient", $message);
    // Redirection avec message de succès
    header("Location: consultation.php?success=Consultation%20ajoutée%20avec%20succès");
    exit;
}
