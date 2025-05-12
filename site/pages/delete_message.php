<?php const ACCESS_ALLOWED = true;
require_once "./config.php";

if (isset($_POST['message_id'], $_SESSION['ID'])) {

    $message_id = intval($_POST['message_id']);
    $user_id = $_SESSION['ID'];

    $stmt = $pdo->prepare("DELETE FROM message WHERE id = :id AND sender_id = :user_id");
    $stmt->bindValue(':id', $message_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirige vers la page de chat
    header("Location: inbox.php?id={$_POST['receiver_id']}&type={$_POST['receiver_type']}");
    exit;
}
