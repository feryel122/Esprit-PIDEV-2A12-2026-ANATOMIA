<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';
require_once __DIR__ . '/../../Model/Forum.php';

requireLogin('login.php');
$controller = new ForumController();
$errors = [];
$title = '';
$content = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'La session a expiré. Rechargez la page.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $errors = $controller->publicationErrors($title, $content);
        if (!$errors) {
            $me = currentUser();
            $forum = new Forum(null, $me['id'], fullName($me), $title, $content);
            $id = $controller->addForum($forum);
            if ($id) {
                setFlash('Publication ajoutée.');
                header('Location: afficherpost.php?id=' . $id);
                exit;
            }
            $errors[] = 'Impossible d\'enregistrer la publication.';
        }
    }
}

$pageTitle = 'Nouvelle publication - ANATOMIA';
$currentPage = 'forum';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <div class="section-heading">
        <h2>Créer une publication</h2>
        <p>Votre nom affiché sera celui de votre compte.</p>
    </div>
    <section class="create-post">
        <?php foreach ($errors as $error): ?><p class="field-error"><?= e($error) ?></p><?php endforeach; ?>
        <form method="post" class="forum-post-form" novalidate>
            <div class="js-errors"></div>
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="text" name="title" placeholder="Titre" value="<?= e($title) ?>">
            <textarea name="content" rows="6" placeholder="Partagez votre expérience ou posez une question"><?= e($content) ?></textarea>
            <button type="submit" class="publish-button">Publier</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
