<!DOCTYPE html>
<?php const ACCESS_ALLOWED = true;
require_once "./config.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    .chat-container {
        max-height: calc(100vh - 10rem); /* Ajuste selon ton layout réel */
        overflow-y: auto;
        padding-bottom: 1rem;
        padding-top: 1rem;
    }
    .sidebar {
        margin-top: 4rem; /* Ajuste selon la hauteur de ta navbar */
    }

    </style>
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
                    <title><?= $site->siteName() ?> - Conversation avec <?= $full_name ?></title> <?php
        } else { ?>
                    <title><?= $site->siteName() ?> - Messagerie</title>
    <?php }
    } else {
        // Si aucune conversation n'est sélectionnée
        ?>
            <title><?= $site->siteName() ?> - Messagerie</title>
    <?php }
    ?>
</head>

<?php
// Récupère tous les messages où l'utilisateur est impliqué
// Récupère tous les messages triés par date décroissante
$request = "SELECT *
            FROM message
            WHERE (sender_id = :user_id AND sender_type = :user_type)
               OR (receiver_id = :user_id AND receiver_type = :user_type)
            ORDER BY date DESC";
$result = $pdo->prepare($request);
$result->bindValue(':user_id', $_SESSION['ID'], PDO::PARAM_INT);
$result->bindValue(':user_type', $_SESSION['user_type'], PDO::PARAM_STR);
$result->execute();
$convs = $result->fetchAll(PDO::FETCH_ASSOC);

// Cache des interlocuteurs
$interlocutors = [];
$seen_ids = [];

foreach ($convs as $conv) {
    $is_sender = $_SESSION['ID'] == $conv['sender_id'] && $_SESSION['user_type'] == $conv['sender_type'];

    $interlocutor_id = $is_sender ? $conv['receiver_id'] : $conv['sender_id'];
    $interlocutor_type = $is_sender ? $conv['receiver_type'] : $conv['sender_type'];

    $unique_key = "{$interlocutor_type}_$interlocutor_id";
    if (isset($seen_ids[$unique_key])) {
        continue;
    }
    $seen_ids[$unique_key] = true;

    // Requête pour récupérer les infos de l’interlocuteur
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
            if ($job) {
                $job_label = $job['libelle'];
            }
        } else {
            $job_label = "patient";
        }

        // Ajoute l’interlocuteur avec la date du dernier message
        $interlocutors[] = [
            'id' => $interlocutor_id,
            'type' => $interlocutor_type,
            'username' => $user['username'],
            'profile_pic' => $user['profile_pic'],
            'job' => $job_label,
            'last_message_date' => $conv['date'] // <-- format timestamp
        ];
    }
}

?>
<body>
<nav class="sidebar menu has-background-light p-3 is-one-quarter" style="width: 300px; height: 100vh; overflow-y: auto;">
    <ul class="menu-list conversation-list">
<?php usort($interlocutors, function ($a, $b) {
    return strtotime($b['last_message_date']) - strtotime($a['last_message_date']);
});

foreach ($interlocutors as $i): ?>
    <li class="conversation-item mb-3">
        <a href="?id=<?= htmlspecialchars($i['id']) ?>&type=<?= htmlspecialchars($i['type']) ?>"
            class="is-flex is-align-items-center p-2 has-background-white box shadow-sm" style="border-radius: 8px;">
            <div class="conversation-card is-flex is-align-items-center">
                <img class="profile-picture mr-3" src="<?= htmlspecialchars($i['profile_pic']) ?>"
                    alt="Photo de <?= htmlspecialchars($i['username']) ?>"
                style="width: 50px; height: 50px; border-radius: 50%;">
            <div class="conversation-info">
                    <p class="username mb-1">Conversation avec <strong><?= htmlspecialchars($i['username']) ?></strong>
                    </p>
                    <p class="job-title has-text-grey"><?= htmlspecialchars($i['job']) ?></p>
                    <p class="last-message-date has-text-grey is-size-7">
                    Dernier message : <?= date("d/m/Y H:i", strtotime($i['last_message_date'])) ?>
                    </p>

                    </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-footer mt-4 px-2">
        <?php switch ($_SESSION['user_type']):
            case 'patient': ?>
                                                <p class="has-text-grey-dark">Vous pouvez également consulter vos messages avec votre médecin.</p>
                                        <?php break;
            case 'medical_staff': ?>
                                                <a href="new_conv.php" class="button is-link is-fullwidth">Nouvelle conversation</a>
                                        <?php break;
        endswitch; ?>
    </div>
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

        // Vérifie si l'utilisateur existe et récupère les messages de la conversation
        if ($user) {
            $request = "SELECT * FROM message WHERE (sender_id = :user_id AND sender_type = :user_type) AND (receiver_id = :receiver_id AND receiver_type = :receiver_type) OR (sender_id = :receiver_id AND sender_type = :receiver_type) AND (receiver_id = :user_id AND receiver_type = :user_type) ORDER BY date";
            $stmt = $pdo->prepare($request);
            $stmt->bindValue(':user_id', $_SESSION['ID'], PDO::PARAM_INT);
            $stmt->bindValue(':user_type', $_SESSION['user_type'], PDO::PARAM_STR);
            $stmt->bindValue(':receiver_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_type', $type, PDO::PARAM_STR);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
            <div class="chat-container p-4" style="max-height: 75vh; overflow-y: auto;">
            <?php foreach ($messages as $message): ?>
                            <div class="message-box mb-3 <?= ($_SESSION['ID'] == $message['sender_id']) ? 'sent has-background-primary-light' : 'received has-background-light' ?> box p-3" style="border-radius: 8px; max-width: 70%; <?= ($_SESSION['ID'] == $message['sender_id']) ? 'margin-left:auto;' : 'margin-right:auto;' ?>">
                                <p class="message-date has-text-grey is-size-7 mb-1"><?= htmlspecialchars($message['date']) ?></p>
                                <p class="message-text"><?= htmlspecialchars($message['content']) ?></p>
        
                                <?php if ($_SESSION['ID'] == $message['sender_id']): ?>
                                                                <form method="POST" action="delete_message.php" class="delete-form mt-2 is-flex is-justify-content-end">
                                                                    <input type="hidden" name="message_id" value="<?= $message['ID'] ?>">
                                                    <input type="hidden" name="receiver_id" value="<?= $id ?>">
                                                    <input type="hidden" name="receiver_type" value="<?= $type ?>">
                    <button type="submit" class="delete-button button is-small is-danger is-light" title="Supprimer ce message">
                    &times;
                    </button>
                </form>
                <?php endif; ?>
                    </div>
            <?php endforeach; ?>
            </div>
        <?php if (!isset($_GET['id'], $_GET['type']) || $_GET['id'] == 0): ?>
            <div class="main-content p-4">
                <p class="is-size-5 has-text-grey">Bienvenue dans votre messagerie !</p>
                <p class="is-size-6">Vous pouvez consulter vos conversations en cliquant sur les utilisateurs à gauche.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['id'], $_GET['type']) && $_GET['id'] != 0): ?>
            <div class="message-form mt-4 p-4 has-background-light is-three-quarters">
                <form method="POST" action="send_message.php" class="is-flex is-flex-direction-column">
                    <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($id) ?>">
                    <input type="hidden" name="receiver_type" value="<?= htmlspecialchars($type) ?>">
                    <textarea name="message" class="textarea mb-3" placeholder="Écrire un message..." required></textarea>
                    <button type="submit" class="button is-link is-fullwidth">Envoyer</button>
                </form>
            </div>
        <?php endif; ?>
            <?php
        }
    } ?>
    </body>

<?php include_once './modules/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.querySelector('.chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>

</html>