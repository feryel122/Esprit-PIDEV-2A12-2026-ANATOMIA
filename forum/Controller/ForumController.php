
<?php

require_once __DIR__ . '/../Model/ForumModel.php';

class ForumController
{
    private $forumModel;

    public function __construct()
    {
        $this->forumModel = new ForumModel();
    }

    // =====================================
    // PUBLICATIONS
    // =====================================

    // Afficher toutes les publications
    public function listPosts()
    {
        return $this->forumModel->getAllPosts();
    }

    // Récupérer une publication
    public function getPost($postId)
    {
        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0
        ) {
            return false;
        }

        return $this->forumModel->getPostById((int) $postId);
    }

    // Créer une publication
    public function createPost($authorName, $title, $content)
    {
        $authorName = trim($authorName);
        $title = trim($title);
        $content = trim($content);

        if ($authorName === '' || $title === '' || $content === '') {
            return false;
        }

        return $this->forumModel->addPost(
            $authorName,
            $title,
            $content
        );
    }

    // Modifier une publication
    public function editPost($postId, $authorName, $title, $content)
    {
        $authorName = trim($authorName);
        $title = trim($title);
        $content = trim($content);

        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0 ||
            $authorName === '' ||
            $title === '' ||
            $content === ''
        ) {
            return false;
        }

        return $this->forumModel->updatePost(
            (int) $postId,
            $authorName,
            $title,
            $content
        );
    }

    // Supprimer une publication
    public function removePost($postId, $authorName)
    {
        $authorName = trim($authorName);

        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0 ||
            $authorName === ''
        ) {
            return false;
        }

        return $this->forumModel->deletePost(
            (int) $postId,
            $authorName
        );
    }

    // =====================================
    // COMMENTAIRES
    // =====================================

    // Ajouter un commentaire
    public function createComment($postId, $authorName, $content)
    {
        $authorName = trim($authorName);
        $content = trim($content);

        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0 ||
            $authorName === '' ||
            $content === ''
        ) {
            return false;
        }

        return $this->forumModel->addComment(
            (int) $postId,
            $authorName,
            $content
        );
    }

    // Récupérer les commentaires
    public function listComments($postId)
    {
        return $this->forumModel->getComments((int) $postId);
    }

    // =====================================
    // LIKES
    // =====================================

    // Compter les likes
    public function countLikes($postId)
    {
        return $this->forumModel->getLikesCount((int) $postId);
    }

    // Vérifier le like d'un membre
    public function memberHasLiked($postId, $memberName)
    {
        return $this->forumModel->hasLiked(
            (int) $postId,
            trim($memberName)
        );
    }

    // Ajouter ou retirer un like
    public function togglePostLike($postId, $memberName)
    {
        $memberName = trim($memberName);

        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0 ||
            $memberName === ''
        ) {
            return false;
        }

        return $this->forumModel->toggleLike(
            (int) $postId,
            $memberName
        );
    }

    // =====================================
    // SIGNALEMENTS
    // =====================================

    // Signaler une publication
    public function reportPost($postId, $reporterName, $reason)
    {
        $reporterName = trim($reporterName);
        $reason = trim($reason);

        if (
            filter_var($postId, FILTER_VALIDATE_INT) === false ||
            $postId <= 0 ||
            $reporterName === '' ||
            $reason === ''
        ) {
            return false;
        }

        return $this->forumModel->addReport(
            (int) $postId,
            $reporterName,
            $reason
        );
    }
}
