
<?php

require_once __DIR__ . '/../Model/ForumStats.php';

class DashboardController
{
    private $forumStats;

    public function __construct()
    {
        $this->forumStats = new ForumStats();
    }

    // =====================================
    // DASHBOARD
    // =====================================

    public function index()
    {
        return [
            'posts' => $this->forumStats->getTotalPosts(),
            'comments' => $this->forumStats->getTotalComments(),
            'likes' => $this->forumStats->getTotalLikes(),
            'reports' => $this->forumStats->getTotalReports()
        ];
    }

    // =====================================
    // PUBLICATIONS
    // =====================================

    public function getPosts()
    {
        return $this->forumStats->getAllPosts();
    }

    public function handlePostAction()
    {
        $this->checkCsrfToken();

        $id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';

        if (!$id) {
            return false;
        }

        if ($action === 'delete') {
            return $this->forumStats->deletePost($id);
        }

        if ($action === 'edit') {
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if ($title === '' || $content === '') {
                return false;
            }

            return $this->forumStats->updatePost(
                $id,
                $title,
                $content
            );
        }

        return false;
    }

    // =====================================
    // COMMENTAIRES
    // =====================================

    public function getComments()
    {
        return $this->forumStats->getAllComments();
    }

    public function handleCommentAction()
    {
        $this->checkCsrfToken();

        $id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';

        if ($id && $action === 'delete') {
            return $this->forumStats->deleteComment($id);
        }

        return false;
    }

    // =====================================
    // SIGNALEMENTS
    // =====================================

    public function getReports()
    {
        return $this->forumStats->getAllReports();
    }

    public function handleReportAction()
    {
        $this->checkCsrfToken();

        $id = filter_input(INPUT_POST, 'report_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';

        if ($action === 'resolve' && $id) {
            return $this->forumStats->resolveReport($id);
        }

        if ($action === 'delete' && $id) {
            return $this->forumStats->deleteReport($id);
        }

        if ($action === 'delete_post' && $id) {
            $postId = filter_input(
                INPUT_POST,
                'post_id',
                FILTER_VALIDATE_INT
            );

            if ($postId) {
                return $this->forumStats->deletePost($postId);
            }
        }

        return false;
    }

    // =====================================
    // SECURITE CSRF
    // =====================================

    private function checkCsrfToken()
    {
        $token = $_POST['csrf_token'] ?? '';

        if (
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            die("Erreur de sécurité.");
        }
    }
}
