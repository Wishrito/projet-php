<?php
const ACCESS_ALLOWED = true;
require_once "./config.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site->siteName() ?> - Accueil</title>
</head>

<body>

    <main class="mt-6">

        <!-- Bannière principale -->
        <section class="hero is-info is-medium has-text-centered is-bold">
            <div class="hero-body" style="background-color: rgba(255, 255, 255, 0.85); border-radius: 10px;">
                <div class="container">
                    <h1 class="title is-2 has-text-dark">SantéPlus</h1>
                    <h2 class="subtitle is-4 has-text-dark">Votre santé, notre priorité</h2>
                </div>
            </div>
        </section>

        <!-- Section À propos -->
        <section class="section">
            <div class="container">
                <h2 class="title is-3 has-text-centered">À propos de SantéPlus</h2>
                <p class="content is-medium has-text-centered">
                    SantéPlus s’engage à offrir des soins de qualité accessibles à tous. Notre équipe de professionnels vous accompagne avec humanité et expertise dans chaque étape de votre santé.
                </p>
            </div>
        </section>

        <!-- Nos services -->
        <section class="section has-background-light">
            <div class="container">
                <h2 class="title is-3 has-text-centered mb-6">Nos services</h2>

                <div class="columns is-multiline is-centered">
                    <div class="column is-4 has-text-centered">
                        <h3 class="title is-5 mt-2">Consultations</h3>
                        <p>Un récapitulatif de vos anciennes et futures consultations</p>
                    </div>
                    <div class="column is-4 has-text-centered">
                        <h3 class="title is-5 mt-2">Messagerie</h3>
                        <p>Un système de messagerie permettant une communication efficace entre le personnel et les patients</p>
                    </div>
                    <div class="column is-4 has-text-centered">
                        <h3 class="title is-5 mt-2">Dossier Médical</h3>
                        <p>Un accès simple au dossier médical des patients</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include_once './modules/footer.php'; ?>

</body>

</html>
