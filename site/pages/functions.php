<?php

function isAlreadyContacted($pdo, $user_id, $sender_type, $receiver_id, $receiver_type) {
    // Vérifie si l'utilisateur a déjà contacté la personne
    $request = "SELECT COUNT(*) FROM message WHERE (sender_id = :user_id AND sender_type = :user_type) AND (receiver_id = :receiver_id AND receiver_type = :receiver_type) OR (sender_id = :receiver_id AND sender_type = :receiver_type) AND (receiver_id = :user_id AND receiver_type = :user_type)";

    $stmt = $pdo->prepare($request);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_type', $sender_type, PDO::PARAM_STR);
    $stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
    $stmt->bindValue(':receiver_type', $receiver_type, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function sendMessage($pdo, $sender_id, $sender_type, $receiver_id, $receiver_type, $message)
{

    // Vérifie si l'utilisateur est un patient et essaie de contacter une nouvelle personne

    if ($sender_type == 'patient' && $receiver_id == $sender_id && $receiver_type == $sender_type) {
        echo "<p>Vous ne pouvez pas vous envoyer de message à vous-même.</p>";
        exit;
    }


    $isOk = isAlreadyContacted($pdo, $sender_id, $sender_type, $receiver_id, $receiver_type);
    if (($isOk && $sender_type == 'patient') || in_array($sender_type, ['admin', 'medical_staff'])) {

        // Vérifie si le type de l'utilisateur est valide
        if (!in_array($receiver_type, ['patient', 'medical_staff'])) {
            echo "<p>Type d'utilisateur invalide.</p>";
            exit;
        }

        // Insère le message dans la base de données
        $request = "INSERT INTO message (sender_id, sender_type, receiver_id, receiver_type, content) VALUES (:sender_id, :sender_type, :receiver_id, :receiver_type, :content)";
        $stmt = $pdo->prepare($request);
        $stmt->bindValue(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindValue(':sender_type', $sender_type, PDO::PARAM_STR);
        $stmt->bindValue(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindValue(':receiver_type', $receiver_type, PDO::PARAM_STR);
        $stmt->bindValue(':content', $message, PDO::PARAM_STR);
        $stmt->execute();
        // Redirige vers la page de chat
        header("Location: inbox.php?id=$receiver_id&type=$receiver_type");
        exit;
    }
}
