<?php
// config.php
if (!defined('ACCESS_ALLOWED')) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied.');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    public function baseUrl(): string
    {
        // Vérifier si les variables $_SERVER nécessaires sont définies
        if (!isset($_SERVER['HTTP_HOST'], $_SERVER['SCRIPT_NAME'])) {
            return '/';
        }

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

$site = new SiteConfig("SantéPlus");
$site->setAttribute('db', 'crm_hopital');
$site->setAttribute('user', 'root');
$site->setAttribute('pass', ''); // Assurez-vous de définir un mot de passe sécurisé

try {
    $pdo = new PDO(
        "mysql:host=" . $site->getAttribute('host') . ";dbname=" . $site->getAttribute('db') . ";charset=utf8",
        $site->getAttribute('user'),
        $site->getAttribute('pass')
    );
    // Définir le mode d'erreur PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE HTML>
<html lang="fr">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../src/css/styles.css">
    <link rel="stylesheet" href="../src/css/form.css">
</head>

<body>
    <div class="block">
        <header class="header">
            <a href="<?php echo $site->baseUrl() . 'index.php'; ?>" class="header-logo">Accueil</a>
            <nav class="header-menu">
                <?php
                $base_url = $site->baseUrl();
                if (!isset($_SESSION['ID'])) { ?>
                            <a href="<?php echo $base_url . 'login.php'; ?>" class="button is-link is-light">Connexion</a>
                            <a href="<?php echo $base_url . 'signup.php'; ?>" class="button is-success is-light">Inscription</a>

                <?php } else { ?>
                            <a href="<?php echo $base_url . 'account.php'; ?>" class="button is-primary is-light">Mon compte</a>
                        <a href="<?php echo $base_url . 'logout.php'; ?>" class="button is-danger is-light">Se déconnecter</a>
                    <?php } ?>
            </nav>
            </header>
        
    </div>
</body>

</html>