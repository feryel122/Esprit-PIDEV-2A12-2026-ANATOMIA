
<?php

session_start();

require_once __DIR__ . '/../Controller/ForumController.php';

$controller = new ForumController();

$pageTitle = 'Forum - ANATOMIA';
$currentPage = 'forum';

// Génération du token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Protection HTML
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        die("Requête invalide. Veuillez actualiser la page.");
    }

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'publish':
            $success = $controller->createPost(
                $_POST['authorName'] ?? '',
                $_POST['postTitle'] ?? '',
                $_POST['postContent'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Publication ajoutée avec succès."
                : "Veuillez remplir tous les champs.";
            break;

        case 'edit':
            $success = $controller->editPost(
                $_POST['post_id'] ?? 0,
                $_POST['author_name'] ?? '',
                $_POST['postTitle'] ?? '',
                $_POST['postContent'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Publication modifiée avec succès."
                : "Impossible de modifier la publication.";
            break;

        case 'delete':
            $success = $controller->removePost(
                $_POST['post_id'] ?? 0,
                $_POST['author_name'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Publication supprimée avec succès."
                : "Impossible de supprimer la publication.";
            break;

        case 'comment':
            $success = $controller->createComment(
                $_POST['post_id'] ?? 0,
                $_POST['comment_author'] ?? '',
                $_POST['comment_content'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Commentaire ajouté."
                : "Impossible d'ajouter le commentaire.";
            break;

        case 'like':
            $success = $controller->togglePostLike(
                $_POST['post_id'] ?? 0,
                $_POST['member_name'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Votre réaction a été enregistrée."
                : "Impossible d'enregistrer votre réaction.";
            break;

        case 'report':
            $success = $controller->reportPost(
                $_POST['post_id'] ?? 0,
                $_POST['reporter_name'] ?? '',
                $_POST['reason'] ?? ''
            );

            $_SESSION['message'] = $success
                ? "Signalement enregistré."
                : "Impossible d'enregistrer le signalement.";
            break;

        default:
            $_SESSION['message'] = "Action inconnue.";
            break;
    }

    header('Location: forum.php');
    exit;
}

// Récupération des publications
$posts = $controller->listPosts();

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

?>

<?php require __DIR__ . '/includes/head.php'; ?>

<body>

<?php require __DIR__ . '/includes/navbar.php'; ?>

<!-- HERO -->
<section class="hero">

    <div class="hero-content">

        <p class="hero-label">NOTRE COMMUNAUTÉ</p>

        <h1>Bienvenue dans la communauté ANATOMIA</h1>

        <p class="hero-description">
            Échangez, partagez vos expériences et découvrez
            les conseils des autres membres.
        </p>

        <a href="#community" class="hero-button">
            Rejoindre la discussion
        </a>

    </div>

</section>

<!-- COMMUNITY -->
<main id="community" class="community">

    <div class="section-heading">
        <h2>Partagez avec la communauté</h2>
        <p>Votre espace d'échange et de discussion</p>
    </div>

    <?php if ($message !== ''): ?>
        <div class="notice">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <!-- CREATE POST -->
    <section class="create-post">

        <h3>Créer une publication</h3>

        <form method="POST" action="forum.php">

            <input type="hidden" name="csrf_token"
                   value="<?= e($_SESSION['csrf_token']) ?>">

            <input type="hidden" name="action" value="publish">

            <input type="text" name="authorName"
                   placeholder="Votre nom" maxlength="100" required>

            <input type="text" name="postTitle"
                   placeholder="Titre de votre publication"
                   maxlength="255" required>

            <textarea name="postContent"
                      placeholder="Partagez votre expérience ou posez une question..."
                      rows="5" required></textarea>

            <button type="submit" class="publish-button">
                Publier
            </button>

        </form>

    </section>

    <!-- POSTS -->
    <section class="posts-section">

        <div class="posts-heading">
            <h3>Les discussions</h3>
            <p>Publications : <span><?= count($posts) ?></span></p>
        </div>

        <div id="postsContainer">

            <?php if (empty($posts)): ?>
                <p class="empty-message">
                    Aucune publication pour le moment.
                    Soyez le premier à partager !
                </p>
            <?php endif; ?>

            <?php foreach ($posts as $post): ?>

                <?php
                $postId = (int) $post['id'];
                $comments = $controller->listComments($postId);
                $likes = $controller->countLikes($postId);
                ?>

                <article class="post-card">

                    <div class="post-header">

                        <div class="user-avatar">
                            <?= e(strtoupper(substr($post['author_name'], 0, 1))) ?>
                        </div>

                        <div class="user-info">
                            <h4><?= e($post['author_name']) ?></h4>
                            <span><?= e($post['created_at']) ?></span>
                        </div>

                    </div>

                    <div class="post-body">
                        <h3><?= e($post['title']) ?></h3>
                        <p><?= nl2br(e($post['content'])) ?></p>
                    </div>

                    <!-- EDIT -->
                    <details class="edit-section">

                        <summary>✏️ Modifier ma publication</summary>

                        <form method="POST" action="forum.php">

                            <input type="hidden" name="csrf_token"
                                   value="<?= e($_SESSION['csrf_token']) ?>">

                            <input type="hidden" name="action" value="edit">

                            <input type="hidden" name="post_id"
                                   value="<?= $postId ?>">

                            <input type="text" name="author_name"
                                   placeholder="Votre nom (nom de l'auteur)"
                                   maxlength="100" required>

                            <input type="text" name="postTitle"
                                   value="<?= e($post['title']) ?>"
                                   maxlength="255" required>

                            <textarea name="postContent" rows="4"
                                      required><?= e($post['content']) ?></textarea>

                            <button type="submit">
                                Enregistrer les modifications
                            </button>

                        </form>

                    </details>

                    <!-- DELETE -->
                    <form method="POST" action="forum.php"
                          class="delete-form"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette publication ?');">

                        <input type="hidden" name="csrf_token"
                               value="<?= e($_SESSION['csrf_token']) ?>">

                        <input type="hidden" name="action" value="delete">

                        <input type="hidden" name="post_id"
                               value="<?= $postId ?>">

                        <input type="text" name="author_name"
                               placeholder="Votre nom (nom de l'auteur)"
                               maxlength="100" required>

                        <button type="submit" class="delete-button">
                            Supprimer la publication
                        </button>

                    </form>

                    <!-- ACTIONS -->
                    <div class="post-actions">

                        <form method="POST" action="forum.php">

                            <input type="hidden" name="csrf_token"
                                   value="<?= e($_SESSION['csrf_token']) ?>">

                            <input type="hidden" name="action" value="like">

                            <input type="hidden" name="post_id"
                                   value="<?= $postId ?>">

                            <input type="text" name="member_name"
                                   placeholder="Votre nom"
                                   maxlength="100" required>

                            <button type="submit">
                                ♡ J'aime (<?= $likes ?>)
                            </button>

                        </form>

                        <button type="button"
                                class="comment-toggle"
                                onclick="toggleComments(<?= $postId ?>)">
                            💬 Commenter (<?= count($comments) ?>)
                        </button>

                    </div>

                    <!-- COMMENTS -->
                    <div class="comments-section"
                         id="comments-<?= $postId ?>" hidden>

                        <h4>Commentaires</h4>

                        <?php foreach ($comments as $comment): ?>

                            <div class="comment-item">
                                <strong>
                                    <?= e($comment['author_name']) ?> :
                                </strong>
                                <?= e($comment['content']) ?>
                            </div>

                        <?php endforeach; ?>

                        <form method="POST" action="forum.php"
                              class="comment-form">

                            <input type="hidden" name="csrf_token"
                                   value="<?= e($_SESSION['csrf_token']) ?>">

                            <input type="hidden" name="action" value="comment">

                            <input type="hidden" name="post_id"
                                   value="<?= $postId ?>">

                            <input type="text" name="comment_author"
                                   placeholder="Votre nom"
                                   maxlength="100" required>

                            <input type="text" name="comment_content"
                                   placeholder="Écrire un commentaire..."
                                   required>

                            <button type="submit">Envoyer</button>

                        </form>

                    </div>

                    <!-- REPORT -->
                    <details class="report-section">

                        <summary>⚑ Signaler cette publication</summary>

                        <form method="POST" action="forum.php">

                            <input type="hidden" name="csrf_token"
                                   value="<?= e($_SESSION['csrf_token']) ?>">

                            <input type="hidden" name="action" value="report">

                            <input type="hidden" name="post_id"
                                   value="<?= $postId ?>">

                            <input type="text" name="reporter_name"
                                   placeholder="Votre nom"
                                   maxlength="100" required>

                            <textarea name="reason"
                                      placeholder="Pourquoi souhaitez-vous signaler cette publication ?"
                                      rows="3" required></textarea>

                            <button type="submit">
                                Envoyer le signalement
                            </button>

                        </form>

                    </details>

                </article>

            <?php endforeach; ?>

        </div>

    </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>

<?php require __DIR__ . '/includes/scripts.php'; ?>
