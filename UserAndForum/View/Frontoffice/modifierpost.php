<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';
require_once __DIR__ . '/../../Model/Forum.php';

requireLogin('login.php');
$controller = new ForumController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$post = $id ? $controller->getForumById($id) : null;
$canEdit = $post && $post['status'] !== 'deleted' && ((int) $post['user_id'] === (int) currentUser()['id'] || userRole() === 'admin');
if (!$canEdit) {
    setFlash('Vous ne pouvez pas modifier cette publication.', 'error');
    header('Location: forum.php');
    exit;
}

$errors = [];
$title = $post['title'];
$content = $post['content'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'La session a expiré.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $errors = $controller->publicationErrors($title, $content);
        if (!$errors) {
            $forum = new Forum($post['id'], $post['user_id'], $post['author_name'], $title, $content, $post['status']);
            if ($controller->updateForum($forum, currentUser()['id'], userRole() === 'admin')) {
                setFlash('Publication modifiée.');
                header('Location: afficherpost.php?id=' . (int) $post['id']);
                exit;
            }
            $errors[] = 'La modification a échoué.';
        }
    }
}

$pageTitle = 'Modifier la publication - ANATOMIA';
$currentPage = 'forum';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <div class="section-heading"><h2>Modifier la publication</h2></div>
    <section class="create-post">
        <?php foreach ($errors as $error): ?><p class="field-error"><?= e($error) ?></p><?php endforeach; ?>
        <form method="post" class="forum-post-form" novalidate>
            <div class="js-errors"></div>
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
            <input type="text" name="title" value="<?= e($title) ?>">
            <textarea name="content" rows="6"><?= e($content) ?></textarea>
            <button type="submit">Enregistrer</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
