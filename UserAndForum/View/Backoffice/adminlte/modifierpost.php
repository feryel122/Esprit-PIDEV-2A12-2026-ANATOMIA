<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../Controller/ForumController.php';
require_once __DIR__ . '/../../../Model/Forum.php';

$controller = new ForumController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$post = $id ? $controller->getForumById($id) : null;
if (!$post || $post['status'] === 'deleted') {
    setFlash('Publication introuvable.', 'error');
    header('Location: posts.php');
    exit;
}

$errors = [];
$titleValue = $post['title'];
$contentValue = $post['content'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'Jeton de sécurité invalide.';
    } else {
        $titleValue = trim($_POST['title'] ?? '');
        $contentValue = trim($_POST['content'] ?? '');
        $errors = $controller->publicationErrors($titleValue, $contentValue);
        if (!$errors) {
            $forum = new Forum($post['id'], $post['user_id'], $post['author_name'], $titleValue, $contentValue, $post['status']);
            if ($controller->updateForum($forum, currentUser()['id'], true)) {
                setFlash('Publication modifiée.');
                header('Location: posts.php');
                exit;
            }
            $errors[] = 'La modification a échoué.';
        }
    }
}

$title = 'ANATOMIA - Modifier une publication';
$currentPage = 'posts';
ob_start();
?>
<div class="card">
    <div class="card-body">
        <h3>Modifier la publication</h3>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endforeach; ?>
        <form method="post" class="row g-3 forum-post-form" novalidate>
            <div class="col-12 js-errors"></div>
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
            <div class="col-12">
                <label class="form-label">Titre</label>
                <input class="form-control" type="text" name="title" value="<?= e($titleValue) ?>">
            </div>
            <div class="col-12">
                <label class="form-label">Contenu</label>
                <textarea class="form-control" name="content" rows="6"><?= e($contentValue) ?></textarea>
            </div>
            <div class="col-12">
                <button class="btn btn-success" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="posts.php">Retour</a>
            </div>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
