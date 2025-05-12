<?php const ACCESS_ALLOWED = true;
require_once "./config.php";

if (isset($_POST['message'], $_POST['receiver_id'], $_POST['receiver_type'])) {
    
    $message = htmlspecialchars($_POST['message']);
    $receiver_id = intval($_POST['receiver_id']);
    $receiver_type = htmlspecialchars($_POST['receiver_type']);

    // Vérifie si le type de l'utilisateur est valide
    if (!in_array($receiver_type, ['patient', 'medical_staff'])) {
        echo "<p>Type d'utilisateur invalide.</p>";
        exit;
    }

    // Insère le message dans la base de données
    $stmt = $pdo->prepare("INSERT INTO message (sender_id, sender_type, receiver_id, receiver_type, content) VALUES (:sender_id, :sender_type, :receiver_id, :receiver_type, :content)");
    $stmt->bindValue(':sender_id', $_SESSION['ID'], PDO::PARAM_INT);
    $stmt->bindValue(':sender_type', $_SESSION['user_type'], PDO::PARAM_STR);
    $stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver_type', $receiver_type, PDO::PARAM_STR);
    $stmt->bindValue(':content', $message, PDO::PARAM_STR);
    $stmt->execute();
    // Redirige vers la page de chat
    header("Location: inbox.php?id=$receiver_id&type=$receiver_type");
    exit;
}