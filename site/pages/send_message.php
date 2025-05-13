<?php const ACCESS_ALLOWED = true;
require_once "./config.php";
require_once "./functions.php";

if (isset($_POST['message'], $_POST['receiver_id'], $_POST['receiver_type'])) {
    // Vérifie si l'utilisateur essaie de contacter lui-même
    if ($_POST['receiver_id'] == $_SESSION['ID'] && $_POST['receiver_type'] == $_SESSION['user_type']) {
        echo "<p>Vous ne pouvez pas vous envoyer de message à vous-même.</p>";
        exit;
    }

    $message = htmlspecialchars($_POST['message']);
    $receiver_id = intval($_POST['receiver_id']);
    $receiver_type = htmlspecialchars($_POST['receiver_type']);

    // Vérifie si le type de l'utilisateur est valide
    if (!in_array($receiver_type, ['patient', 'medical_staff'])) {
        echo "<p>Type d'utilisateur invalide.</p>";
        exit;
    }

    // Insère le message dans la base de données
    sendMessage($pdo, $_SESSION['ID'], $_SESSION['user_type'], $receiver_id, $receiver_type, $message);
    header("Location: inbox.php?id=$receiver_id&type=$receiver_type");
    exit;
}