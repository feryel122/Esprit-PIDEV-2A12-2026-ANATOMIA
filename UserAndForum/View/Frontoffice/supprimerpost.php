<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';

requireLogin('login.php');
$controller = new ForumController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$post = $id ? $controller->getForumById($id) : null;
$canDelete = $post && $post['status'] !== 'deleted' && ((int) $post['user_id'] === (int) currentUser()['id'] || userRole() === 'admin');
if (!$canDelete) {
    setFlash('Vous ne pouvez pas supprimer cette publication.', 'error');
    header('Location: forum.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        setFlash('La session a expiré.', 'error');
    } elseif ($controller->deleteForum($post['id'], currentUser()['id'], userRole() === 'admin')) {
        setFlash('Publication supprimée.');
        header('Location: forum.php');
        exit;
    } else {
        setFlash('Suppression impossible.', 'error');
    }
    header('Location: supprimerpost.php?id=' . (int) $post['id']);
    exit;
}

$pageTitle = 'Supprimer la publication - ANATOMIA';
$currentPage = 'forum';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <section class="create-post">
        <h2>Supprimer cette publication ?</h2>
        <p><strong><?= e($post['title']) ?></strong></p>
        <p>Elle ne sera plus visible dans le forum.</p>
        <form method="post" class="delete-form" onsubmit="return confirm('Confirmer la suppression ?');">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
            <button type="submit" class="delete-button">Supprimer</button>
        </form>
        <a href="afficherpost.php?id=<?= (int) $post['id'] ?>">Annuler</a>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
