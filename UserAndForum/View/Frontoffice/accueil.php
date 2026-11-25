<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$pageTitle = 'Accueil - ANATOMIA';
$currentPage = 'accueil';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>

<section class="hero">
    <div class="hero-content">
        <p class="hero-label">SANTÉ NATURELLE</p>
        <h1>Bienvenue sur ANATOMIA</h1>
        <p class="hero-description">
            Un espace pour comprendre le corps, échanger avec la communauté
            et trouver des conseils adaptés à votre profil.
        </p>
        <a href="forum.php" class="hero-button">Entrer dans le forum</a>
    </div>
</section>

<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <div class="section-heading">
        <h2>La communauté ANATOMIA</h2>
        <p>Publications, commentaires et échanges entre membres et médecins.</p>
    </div>
    <section class="profile-card">
        <h3>Ce que vous pouvez faire</h3>
        <p>Créer un compte, publier un sujet, commenter, aimer et signaler un contenu.</p>
        <p>Les médecins disposent d'un espace identifié. L'administration modère les comptes et les publications.</p>
        <div class="inline-actions">
            <a href="ajoutuser.php">Créer un compte</a>
            <a href="login.php" class="ghost-button">Se connecter</a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
