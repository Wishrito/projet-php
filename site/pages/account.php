<?php
include_once '../config.php'; 

//rajouter If user = patient
$query = $bdd->prepare('SELECT email, first_name, last_name, birth_date, leaving_date, floor_lvl, admission_date FROM patient WHERE ID = ? ');
$query->execute([$_SESSION['ID']]);

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site->siteName() ?> - Compte</title>
</head>
<body>
    
</body>
</html>