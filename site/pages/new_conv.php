<?php const ACCESS_ALLOWED = true;
require_once "./config.php";
// Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
if (!isset($_SESSION['ID'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['user']) ? intval($_GET['user']) : null;

// Récupération du groupe sélectionné, par défaut "medical_staff"
$group = isset($_GET['group']) ? $_GET['group'] : 'medical_staff';
// Vérification sécurité : seulement patient ou medical_staff
if (!in_array($group, ['medical_staff', 'patient'])) {
    $group = 'medical_staff';
}


switch ($_SERVER['REQUEST_METHOD']):
    case "POST":
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
endswitch;

if ($_SESSION['user_type'] != 'medical_staff') {
    header("Location: index.php");
    exit;
}
// Récupère tous les utilisateurs
// Préparation et exécution de la requête
$stmt = $pdo->prepare("SELECT id, first_name, last_name FROM $group WHERE id <> :id or first_name <> NULL ORDER BY username");
$stmt->bindValue(':id', $_SESSION['ID'], PDO::PARAM_INT);
$stmt->execute();

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title><?php echo $site->siteName() ?> - Nouvelle conversation</title>
    <body>

    <form id="user-form" method="GET" action="">
        <label for="group" selected value="">Choisissez une option :</label>
        <select name="group" id="group" onchange="submitForm()">
            <?php if (!isset($id) || !isset($group)) {?>
            <option value="" selected disabled>Choisissez un utilisateur</option>
            <?php } ?>
            <option value="medical_staff" <?= $group === 'medical_staff' ? 'selected' : '' ?>>Personnel médical</option>
                <option value="patient" <?= $group === 'patient' ? 'selected' : '' ?>>Patient</option>
            </select>
            <label for="user">Choisissez un utilisateur :</label>
            <select name="user" id="" onchange="submitForm()">
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['id']}'>{$row['last_name']} {$row['first_name']}</option>";
                } ?>
            </select>
        </form>
        <form id="msg-form" method="POST" action="send_message.php">

        <?php if (isset($id) && isset($group)) { ?>
            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($id) ?>">
            <input type="hidden" name="receiver_type" value="<?= htmlspecialchars($group) ?>">
            <?php } ?>
            <label for="message">Message :</label>
            <textarea name="message" id="message" rows="4" required></textarea>
            <button type="submit">Contacter</button>
        </form>

<footer class="footer">
<script>
function submitForm() {
  document.getElementById('user-form').submit();
}
</script>
<div>
    <p>© 2025 <?= $site->siteName() ?>. Tous droits réservés.</p>
</div>
</footer>
</body>