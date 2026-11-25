<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';
require_once __DIR__ . '/../../Controller/UserController.php';

requireLogin('login.php');
requireRole(['medecin'], 'forum.php');

$users = new UserController();
$forum = new ForumController();
$account = $users->getUserById(currentUser()['id']);
$posts = $forum->getForumsByUser($account['id']);

$pageTitle = 'Espace médecin - ANATOMIA';
$currentPage = 'medecin';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <section class="hero" style="width:100%;margin:0 0 30px;">
        <div class="hero-content">
            <p class="hero-label">ESPACE MÉDECIN</p>
            <h1>Dr <?= e(fullName($account)) ?></h1>
            <p class="hero-description"><?= e($account['specialite'] ?: 'Médecin de la communauté ANATOMIA') ?></p>
        </div>
    </section>
    <div class="section-heading">
        <h2>Vos publications</h2>
        <p>Vous pouvez publier, commenter et signaler comme les autres membres. Votre nom est identifié comme médecin.</p>
    </div>
    <?php if (!$posts): ?>
        <p class="empty-message">Vous n'avez pas encore publié. <a href="ajoutpost.php">Créer une publication</a></p>
    <?php endif; ?>
    <?php foreach ($posts as $post): ?>
        <article class="post-card">
            <div class="post-body">
                <h3><?= e($post['title']) ?></h3>
                <p><?= nl2br(e($post['content'])) ?></p>
            </div>
            <div class="inline-actions">
                <a href="afficherpost.php?id=<?= (int) $post['id'] ?>">Voir</a>
                <a href="modifierpost.php?id=<?= (int) $post['id'] ?>">Modifier</a>
            </div>
        </article>
    <?php endforeach; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
