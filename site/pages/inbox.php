<!DOCTYPE html>
<?php const ACCESS_ALLOWED = true;
require_once "./config.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php if (isset($_GET['id']) && isset($_GET['type'])) {
        // Si une conversation est sélectionnée
        $id = $_GET['id'];
        $type = $_GET['type'];
        // Vérifie si l'utilisateur a accès à cette conversation
        if (!in_array($type, ['patient', 'medical_staff'])) {
            echo "<p>Type d'utilisateur invalide.</p>";
            exit;
        }
        $stmt = $pdo->prepare("SELECT * FROM $type WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $last_name = $user['last_name'];
            $first_name = $user['username'];
            $full_name = "";
            if ($last_name == $first_name) {
                $full_name = $first_name;
            } else {
                $full_name = "$last_name $first_name";
            }
            ?>
            <title><?php echo $site->siteName() ?> - Conversation avec <?php echo $full_name ?></title> <?php
        } else { ?>
                    <title><?php echo $site->siteName() ?> - Messagerie</title>
    <?php }
    } else {
        // Si aucune conversation n'est sélectionnée
        ?>
    <title><?php echo $site->siteName() ?> - Messagerie</title>
    <?php }
    ?>
</head>

<?php
// Récupère tous les messages où l'utilisateur est impliqué
$request = "SELECT *
            FROM message
            WHERE (sender_id = :user_id AND sender_type = :user_type)
               OR (receiver_id = :user_id AND receiver_type = :user_type)";
$result = $pdo->prepare($request);
$result->bindValue(':user_id', $_SESSION['ID'], PDO::PARAM_INT);
$result->bindValue(':user_type', $_SESSION['user_type'], PDO::PARAM_STR);
$result->execute();
$convs = $result->fetchAll(PDO::FETCH_ASSOC);

// Cache des utilisateurs déjà traités
$interlocutors = [];
$seen_ids = [];

foreach ($convs as $conv) {
    // Détermine si l'utilisateur est l'expéditeur ou le destinataire
    $is_sender = $_SESSION['ID'] == $conv['sender_id'] && $_SESSION['user_type'] == $conv['sender_type'];

    $interlocutor_id = $is_sender ? $conv['receiver_id'] : $conv['sender_id'];
    $interlocutor_type = $is_sender ? $conv['receiver_type'] : $conv['sender_type'];

    // Empêche de traiter deux fois la même personne
    $unique_key = $interlocutor_type . '_' . $interlocutor_id;
    if (isset($seen_ids[$unique_key])) {
        continue;
    }
    $seen_ids[$unique_key] = true;

    // Requête pour obtenir les infos de l'interlocuteur
    $stmt = $pdo->prepare("SELECT * FROM $interlocutor_type WHERE id = :id");
    $stmt->bindValue(':id', $interlocutor_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $job_label = "Aucun poste";
        if (isset($user['job'])) {
            $job_stmt = $pdo->prepare("SELECT libelle FROM job WHERE id = :id");
            $job_stmt->bindValue(':id', $user['job'], PDO::PARAM_INT);
            $job_stmt->execute();
            $job = $job_stmt->fetch(PDO::FETCH_ASSOC);
        }
        if ($job) {
            $job_label = $job['libelle'];
        } else {
            $job_label = "patient";
        }

        // Stockage de l’interlocuteur dans un tableau structuré
        $interlocutors[] = [
            'id' => $interlocutor_id,
            'type' => $interlocutor_type,
            'username' => $user['username'],
            'profile_pic' => $user['profile_pic'],
            'job' => $job_label
        ];
    }
}
?>
<body>
    <nav class="sidebar">
        <ul class="conversation-list">
            <?php foreach ($interlocutors as $i): ?>
                <li class="conversation-item">
                    <a href="?id=<?= htmlspecialchars($i['id']) ?>&type=<?= htmlspecialchars($i['type']) ?>">
                        <div class="conversation-card">
                        <img class="profile-picture" src="<?= htmlspecialchars($i['profile_pic']) ?>"
                            alt="Photo de <?= htmlspecialchars($i['username']) ?>">
                        <div class="conversation-info">
                            <p class="username">Conversation
                                avec <strong><?= htmlspecialchars($i['username']) ?></strong>
                            </p>
                            <p class="job-title"><?= htmlspecialchars($i['job']) ?></p>
                    </div>
                </div></a>
            </li>
        <?php endforeach; ?>
        </ul>
        <div class="sidebar-footer">
            
            <?php switch ($_SESSION['user_type']):
                case 'patient': ?>
                    <p>Vous pouvez également consulter vos messages avec votre médecin.</p>
                    <?php break;
                case 'medical_staff': ?>
                    <a href="new_conv.php" class="button">Nouvelle conversation</a>
                    <?php break;
            endswitch;
            ?>
    </nav>
    <?php if (isset($_GET['id']) && isset($_GET['type'])) {
        // Si une conversation est sélectionnée
        $id = $_GET['id'];
        $type = $_GET['type'];

        // Vérifie si l'utilisateur a accès à cette conversation
        if (!in_array($type, ['patient', 'medical_staff'])) {
            echo "<p>Type d'utilisateur invalide.</p>";
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM $type WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $request = "SELECT * FROM message WHERE (sender_id = :user_id AND sender_type = :user_type) AND (receiver_id = :receiver_id AND receiver_type = :receiver_type) OR (sender_id = :receiver_id AND sender_type = :receiver_type) AND (receiver_id = :user_id AND receiver_type = :user_type) ORDER BY date";
            $stmt = $pdo->prepare($request);
            $stmt->bindValue(':user_id', $_SESSION['ID'], PDO::PARAM_INT);
            $stmt->bindValue(':user_type', $_SESSION['user_type'], PDO::PARAM_STR);
            $stmt->bindValue(':receiver_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_type', $type, PDO::PARAM_STR);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<div class='chat-container'>";

            foreach ($messages as $message): ?>
                    <div class="message <?= ($_SESSION['ID'] == $message['sender_id']) ? 'sent' : 'received' ?>">
                    <p class="message-date"><?= htmlspecialchars($message['date']) ?></p>
    <p class="message-text"><?= htmlspecialchars($message['content']) ?></p>

    <?php if ($_SESSION['ID'] == $message['sender_id']): ?>
        <form method="POST" action="delete_message.php" class="delete-form">
            <input type="hidden" name="message_id" value="<?= $message['ID'] ?>">
            <input type="hidden" name="receiver_id" value="<?= $id ?>">
            <input type="hidden" name="receiver_type" value="<?= $type ?>">
            <button type="submit" class="delete-button" title="Supprimer ce message">&times;</button>
        </form>
    <?php endif; ?>
</div>

<?php endforeach; ?>
</div>

<?php
        } else {
            echo "<p>Utilisateur introuvable.</p>";
        }
    } else { ?>
<div class="main-content">
    <p>Bienvenue dans votre messagerie !</p>
    <p>Vous pouvez consulter vos conversations en cliquant sur les utilisateurs à gauche.</p>
            <?php }
    if (isset($_GET['id']) && isset($_GET['type'])) { ?>
                <div class="message-form">
                    <form method="POST" action="send_message.php">
                        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($id) ?>">
                        <input type="hidden" name="receiver_type" value="<?= htmlspecialchars($type) ?>">
                        <textarea name="message" placeholder="Écrire un message..." required></textarea>
                        <button type="submit">Envoyer</button>
                    </form>
                </div><?php
    } ?>
    </body>

<footer class="footer">
     <div>
        <p>© 2025 <?= $site->siteName() ?>. Tous droits réservés.</p>
     </div>
     <script>
    const chatContainer = document.querySelector('.chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>

</footer>

</html>