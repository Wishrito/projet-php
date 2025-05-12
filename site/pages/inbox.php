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
    <title><?php echo $site->siteName() ?> - Messagerie</title>
</head>

<?php
// Récupère tous les messages où l'utilisateur est impliqué
$request = "SELECT sender_id, sender_type, receiver_id, receiver_type 
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
    $is_sender = ($_SESSION['ID'] == $conv['sender_id'] && $_SESSION['user_type'] == $conv['sender_type']);

    $interlocutor_id = $is_sender ? $conv['receiver_id'] : $conv['sender_id'];
    $interlocutor_type = $is_sender ? $conv['receiver_type'] : $conv['sender_type'];

    // Empêche de traiter deux fois la même personne
    $unique_key = $interlocutor_type . '_' . $interlocutor_id;
    if (isset($seen_ids[$unique_key]))
        continue;
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
                    <div class="conversation-card">
                        <img class="profile-picture" src="<?= htmlspecialchars($i['profile_pic']) ?>"
                            alt="Photo de <?= htmlspecialchars($i['username']) ?>">
                        <div class="conversation-info">
                            <p class="username">Conversation avec <strong><?= htmlspecialchars($i['username']) ?></strong>
                            </p>
                            <p class="job-title"><?= htmlspecialchars($i['job']) ?></p>
                    </div>
                </div>
            </li>
    <?php endforeach; ?>
    </ul>

        </nav>

</body>

<footer class="footer">
     <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>