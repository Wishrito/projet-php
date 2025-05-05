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
    <title><?php echo $site->siteName() ?> - Messagerie</title>
</head>

<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messages = [];
    $sender_request = "SELECT * FROM messages WHERE sender_id = ? and sender_type = ? ORDER BY DATE DESC";
    $receiver_request = "SELECT * FROM messages WHERE receiver_id = ? and receiver_type = ? ORDER BY DATE DESC";

    $sender_requete = $pdo->prepare($sender_request);
    $receiver_requete = $pdo->prepare($receiver_request);

    $sender_requete->execute([$_SESSION['sender_id'], $_SESSION['user_type']]);
    $receiver_requete->execute([$_SESSION['receiver_id'], $_SESSION['user_type']]);

    $sender_messages = $sender_requete->fetchAll(PDO::FETCH_ASSOC);
    $receiver_messages = $receiver_requete->fetchAll(PDO::FETCH_ASSOC);

    // ajouter les messages à la liste des messages
    $messages = array_merge($sender_messages, $receiver_messages);
    if ($messages) {

        $convs_request = "SELECT DISTINCT sender_id, receiver_id, sender_type, receiver_type
                        FROM messages WHERE
                        sender_id != ? and sender_type = ?
                        or receiver_id != ? and receiver_type = ?";
        $convs_requete = $pdo->prepare($convs_request);
        $convs_requete->execute([$_SESSION['sender_id'], $_SESSION['user_type'], $_SESSION['receiver_id'], $_SESSION['user_type']]);
        $convs = $convs_requete->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div style="width: 300px; height: 200px; overflow: auto; border: 1px solid black;">

            <ul>
                <?php

                foreach ($convs as $conv) {
                    if ($conv['sender_id'] == $_SESSION['ID'] && $conv['sender_type'] == $_SESSION['user_type']) {
                        $contact_id = $conv['receiver_id'];
                        $contact_type = $conv['receiver_type'];
                        $contact_request = "SELECT username, first_name, last_name FROM $contact_type WHERE id = ?";

                    } elseif ($conv['receiver_id'] == $_SESSION['ID'] && $conv['receiver_type'] == $_SESSION['user_type']) {
                        $contact_id = $conv['sender_id'];
                        $contact_type = $conv['sender_type'];
                        $contact_request = "SELECT username, first_name, last_name FROM $contact_type WHERE id = ?";
                    }

                    $contact_requete = $pdo->prepare($contact_request);
                    $contact_requete->execute([$contact_id]);
                    $contact = $contact_requete->fetch(PDO::FETCH_ASSOC);
                    $first_name = $contact['first_name'];
                    $last_name = $contact['last_name'];
                    $username = $contact['username'];
                    ?>
                    <li>
                        <div>
                            <a
                                href="?id=<?php echo htmlspecialchars($conv['sender_id']); ?>"><?php echo "$last_name $first_name" ?></a>

                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?php
        $user_select = "SELECT username FROM $user_type WHERE id = ? or id = ?";
        $requete = $pdo->prepare($user_select);
        $requete->execute([$_SESSION['sender_id'], $_SESSION['receiver_id']]);

        usort($messages, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });
        ?>
        <p><strong>De:</strong><?php echo htmlspecialchars($message['sender_id']); ?></p><?php
           foreach ($messages as $message) { ?>
            <div class='message'>
                <p><strong>Message:</strong><?php echo htmlspecialchars($message['message']); ?></p>
                <p><strong>Date:</strong><?php echo htmlspecialchars($message['date']); ?></p>
            </div>
        <?php }
    } else { ?>
        <div class='notification is-info'>Aucun message trouvé.</div>
    <?php }
}


?>


</body>

<footer class="footer">
     <div>
        <p>© 2025 <?php echo $site->siteName() ?>. Tous droits réservés.</p>
     </div>
</footer>

</html>