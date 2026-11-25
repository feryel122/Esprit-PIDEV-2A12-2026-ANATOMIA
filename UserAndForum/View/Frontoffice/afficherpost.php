<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/ForumController.php';

$controller = new ForumController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$post = $id ? $controller->getForumById($id) : null;
if (!$post || ($post['status'] !== 'active' && userRole() !== 'admin')) {
    setFlash('Publication introuvable.', 'error');
    header('Location: forum.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireLogin('login.php');
    if (!verifyCsrf()) {
        setFlash('La session a expiré.', 'error');
    } else {
        $action = $_POST['action'] ?? '';
        $me = currentUser();
        if ($action === 'comment') {
            $text = trim($_POST['content'] ?? '');
            $commentErrors = $controller->commentErrors($text);
            if ($commentErrors) {
                setFlash($commentErrors[0], 'error');
            } elseif ($controller->addComment($post['id'], $me['id'], fullName($me), $text)) {
                setFlash('Commentaire ajouté.');
            } else {
                setFlash('Impossible d\'ajouter le commentaire.', 'error');
            }
        } elseif ($action === 'like') {
            $controller->toggleLike($post['id'], $me['id'], fullName($me));
        } elseif ($action === 'report') {
            $reason = trim($_POST['reason'] ?? '');
            $reportErrors = $controller->reportErrors($reason);
            if ($reportErrors) {
                setFlash($reportErrors[0], 'error');
            } elseif ($controller->addReport($post['id'], $me['id'], fullName($me), $reason)) {
                setFlash('Signalement enregistré.');
            } else {
                setFlash('Signalement impossible (déjà envoyé, ou c\'est votre publication).', 'error');
            }
        } elseif ($action === 'delete_comment') {
            $commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
            if ($commentId && $controller->deleteComment($commentId, $me['id'], userRole() === 'admin')) {
                setFlash('Commentaire supprimé.');
            } else {
                setFlash('Suppression refusée.', 'error');
            }
        }
    }
    header('Location: afficherpost.php?id=' . (int) $post['id']);
    exit;
}

$comments = $controller->getCommentsByPost($post['id']);
$liked = isLoggedIn() && $controller->hasLiked($post['id'], currentUser()['id']);
$pageTitle = $post['title'] . ' - ANATOMIA';
$currentPage = 'forum';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <article class="post-card">
        <div class="post-header">
            <div class="user-avatar"><?= e(strtoupper(substr($post['author_name'], 0, 1))) ?></div>
            <div class="user-info">
                <h4>
                    <?= e($post['author_name']) ?>
                    <?php if (($post['author_role'] ?? '') === 'medecin'): ?><span class="badge-role">Médecin</span><?php endif; ?>
                </h4>
                <span><?= e($post['created_at']) ?></span>
            </div>
        </div>
        <div class="post-body">
            <h3><?= e($post['title']) ?></h3>
            <p><?= nl2br(e($post['content'])) ?></p>
        </div>
        <?php if (isLoggedIn() && ((int) $post['user_id'] === (int) currentUser()['id'] || userRole() === 'admin')): ?>
            <div class="inline-actions">
                <a href="modifierpost.php?id=<?= (int) $post['id'] ?>">Modifier</a>
                <a href="supprimerpost.php?id=<?= (int) $post['id'] ?>">Supprimer</a>
            </div>
        <?php endif; ?>

        <?php if (isLoggedIn()): ?>
            <form method="post" class="post-actions">
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="action" value="like">
                <button type="submit"><?= $liked ? 'Retirer j\'aime' : 'J\'aime' ?> (<?= (int) $post['likes_count'] ?>)</button>
            </form>
        <?php else: ?>
            <p><?= (int) $post['likes_count'] ?> j'aime</p>
        <?php endif; ?>
    </article>

    <section class="create-post">
        <h3>Commentaires (<?= count($comments) ?>)</h3>
        <?php if (!$comments): ?><p class="empty-message">Aucun commentaire.</p><?php endif; ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment-item">
                <strong>
                    <?= e($comment['author_name']) ?>
                    <?php if (($comment['author_role'] ?? '') === 'medecin'): ?><span class="badge-role">Médecin</span><?php endif; ?>
                </strong>
                <p><?= nl2br(e($comment['content'])) ?></p>
                <span><?= e($comment['created_at']) ?></span>
                <?php if (isLoggedIn() && ((int) $comment['user_id'] === (int) currentUser()['id'] || userRole() === 'admin')): ?>
                    <form method="post" onsubmit="return confirm('Supprimer ce commentaire ?');">
                        <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                        <input type="hidden" name="action" value="delete_comment">
                        <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">
                        <button type="submit" class="delete-button">Supprimer</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (isLoggedIn()): ?>
            <form method="post" class="comment-form" novalidate>
                <div class="js-errors"></div>
                <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="action" value="comment">
                <input type="text" name="content" placeholder="Écrire un commentaire">
                <button type="submit">Envoyer</button>
            </form>
            <details class="report-section">
                <summary>Signaler cette publication</summary>
                <form method="post" novalidate>
                    <div class="js-errors"></div>
                    <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="action" value="report">
                    <textarea name="reason" rows="3" placeholder="Pourquoi signalez-vous ce contenu ?"></textarea>
                    <button type="submit">Envoyer le signalement</button>
                </form>
            </details>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
