<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';

$controller = new ForumController();
$search = trim($_GET['q'] ?? '');
$searchErrors = $controller->searchErrors($search);
$posts = $searchErrors ? [] : $controller->searchForums($search);

$pageTitle = 'Forum - ANATOMIA';
$currentPage = 'forum';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>

<section class="hero">
    <div class="hero-content">
        <p class="hero-label">NOTRE COMMUNAUTÉ</p>
        <h1>Bienvenue dans la communauté ANATOMIA</h1>
        <p class="hero-description">
            Échangez, partagez vos expériences et découvrez les conseils des autres membres.
        </p>
        <a href="#community" class="hero-button">Rejoindre la discussion</a>
    </div>
</section>

<main id="community" class="community">
    <div class="section-heading">
        <h2>Partagez avec la communauté</h2>
        <p>Votre espace d'échange et de discussion</p>
    </div>
    <?php require __DIR__ . '/includes/flash.php'; ?>

    <?php foreach ($searchErrors as $error): ?><p class="field-error"><?= e($error) ?></p><?php endforeach; ?>
    <form class="search-bar forum-search-form" method="get" action="forum.php" novalidate>
        <div class="js-errors"></div>
        <input type="text" name="q" placeholder="Rechercher une publication" value="<?= e($search) ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (isLoggedIn()): ?>
        <div class="inline-actions">
            <a href="ajoutpost.php">Nouvelle publication</a>
        </div>
    <?php else: ?>
        <p class="notice">Connectez-vous pour publier, commenter ou signaler.</p>
    <?php endif; ?>

    <section class="posts-section">
        <div class="posts-heading">
            <h3>Les discussions</h3>
            <p>Publications : <span><?= count($posts) ?></span></p>
        </div>
        <?php if (!$posts): ?>
            <p class="empty-message">Aucune publication pour le moment.</p>
        <?php endif; ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <div class="post-header">
                    <div class="user-avatar"><?= e(strtoupper(substr($post['author_name'], 0, 1))) ?></div>
                    <div class="user-info">
                        <h4>
                            <?= e($post['author_name']) ?>
                            <?php if (($post['author_role'] ?? '') === 'medecin'): ?>
                                <span class="badge-role">Médecin</span>
                            <?php endif; ?>
                        </h4>
                        <span><?= e($post['created_at']) ?></span>
                    </div>
                </div>
                <div class="post-body">
                    <h3><?= e($post['title']) ?></h3>
                    <p><?= nl2br(e(mb_strimwidth($post['content'], 0, 280, '…'))) ?></p>
                </div>
                <div class="inline-actions">
                    <a href="afficherpost.php?id=<?= (int) $post['id'] ?>">Lire · <?= (int) $post['comments_count'] ?> commentaires · <?= (int) $post['likes_count'] ?> j'aime</a>
                    <?php if (isLoggedIn() && ((int) $post['user_id'] === (int) currentUser()['id'] || userRole() === 'admin')): ?>
                        <a href="modifierpost.php?id=<?= (int) $post['id'] ?>">Modifier</a>
                        <a href="supprimerpost.php?id=<?= (int) $post['id'] ?>">Supprimer</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
