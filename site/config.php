<?php

session_start();

class SiteConfig
{
    private $attributes = [
        'site_name' => '',
        'base_url' => '',
        'host' => '',
        'db' => '',
        'user' => '',
        'pass' => '',
        // Add more attributes here as needed
    ];

    // Constructor to initialize attributes
    public function __construct($site_name = '', $host = 'localhost', $db = '', $user = 'root', $pass = '')
    {
        $this->setAttribute('site_name', $site_name);
        $this->setAttribute('host', $host);
        $this->setAttribute('db', $db);
        $this->setAttribute('user', $user);
        $this->setAttribute('pass', $pass);
    }

    // function to get the base path
    function base_url(): string
    {
        // Détecter si HTTPS est activé
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        // Récupérer le nom de domaine et le port
        $domainName = $_SERVER['HTTP_HOST'];

        // Chemin de base du script (si l'application est dans un sous-dossier)
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

        // Construire et retourner l'URL complète
        return "$protocol$domainName$basePath/";
    }

    // Generic setter for any attribute
    public function setAttribute(string $key, string $value): void
    {
        $this->attributes[$key] = $value;
    }

    // Generic getter for any attribute
    public function getAttribute(string $key): string|null
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    // Getter for all attributes
    public function getAllAttributes(): array
    {
        return $this->attributes;
    }

    public function setSiteName(string $site_name): void
    {
        $this->attributes['site_name'] = $site_name;
    }

    public function siteName(): string
    {
        return $this->attributes['site_name'];
    }
}

$site = new SiteConfig("CRM Hopital");
$site->setAttribute('db', 'crm_hopital');
$site->setAttribute('user', 'root');
$pass = '';

try {
    $pdo = new PDO("mysql:host=" . $site->getAttribute('host') . ";dbname=" . $site->getAttribute('db') . ";charset=utf8", $site->getAttribute('user'), $site->getAttribute('pass'));
    // Définir le mode d'erreur PDO sur Exception

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<div class="block">
<header class="header">
    <a href="index.php" class="header-logo">Accueil</a>
    <nav class="header-menu">
        <?php
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['email'])) {
            // Si connecté, afficher l'email de l'utilisateur et un lien de déconnexion
            echo '<a href="pages/account.php">Mon compte</a>';
            echo '<a href="pages/logout.php">Se déconnecter</a>';
        } else {
            // Si non connecté, afficher les liens de connexion et d'inscription
            echo '<a href="pages/login.php">Connexion</a>';
            echo '<a href="pages/signup.php">Inscription</a>';
        }
        ?>
    </nav>
</header>
</div>
