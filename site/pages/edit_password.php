<?php
const ACCESS_ALLOWED = true;
include_once "./config.php";

if ($_SESSION['job'] != 75) {
    header("Location: index.php");
?>
    <?php
} elseif (isset($_POST['user_id'], $_POST['user_type'], $_POST['new-password'])) {
    $new_password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
    $request = "UPDATE {$_POST['user_type']} SET password = :pwd WHERE id = :id";
    $stmt = $pdo->prepare($request);
    $stmt->bindValue(":id", $_POST['user_id']);
    $stmt->bindValue(":pwd", $new_password);
    $stmt->execute();
    header("Location: users.php");
}