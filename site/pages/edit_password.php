<?php
const ACCESS_ALLOWED = true;
include_once "./config.php";

if ($_SESSION['job'] != 75) {
    header("Location: index.php");
?>
    <?php
} elseif (isset($_POST['user_id'], $_POST['user_type'], $_POST['new-password'])) {
    $new_password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);
    $now = new DateTime();
    $request = "UPDATE {$_POST['user_type']} SET password = :pwd last_password_edit = :last_pwd WHERE id = :id";
    $stmt = $pdo->prepare($request);
    $stmt->bindValue(":id", $_POST['user_id']);
    $stmt->bindValue(":pwd", $new_password);
    $stmt->bindValue(":last_pwd", $now->format('Y-m-d H:i:s'));
    $stmt->execute();
    header("Location: users.php");
}