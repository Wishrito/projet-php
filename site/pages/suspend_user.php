<?php
const ACCESS_ALLOWED = true;
require_once './config.php';

if (!isset($_SESSION['ID'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $current_state = $_POST['state'];
    $user_type = $_POST['user_type'];

    
    if ($user_id !== false && $current_state !== false) {
        $state = ($current_state == 1) ? 0 : 1;

        try {
            $request = "UPDATE $user_type SET is_suspended = :is_suspended WHERE ID = :id";
            $stmt = $pdo->prepare($request);
            $stmt->bindValue(":is_suspended", $state, PDO::PARAM_INT);
            $stmt->bindValue(":id", $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: users.php');
            exit();
        } catch (PDOException $e) {
            // Gestion des erreurs
            die('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    } else {
        die('Données invalides.');
    }
}
