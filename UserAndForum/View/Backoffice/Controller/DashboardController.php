<?php

require_once __DIR__ . '/../../../Controller/ForumController.php';
require_once __DIR__ . '/../../../Controller/UserController.php';

class DashboardController
{
    private $forum;
    private $users;

    public function __construct()
    {
        $this->forum = new ForumController();
        $this->users = new UserController();
    }

    public function index()
    {
        $stats = $this->forum->getStats();
        $stats['users'] = $this->users->countUsers();
        $stats['medecins'] = $this->users->countByRole('medecin');
        return $stats;
    }

    public function getPosts()
    {
        return $this->forum->getAllForumsAdmin($_GET['q'] ?? '');
    }

    public function handlePostAction()
    {
        if (!$this->checkCsrfToken()) {
            return false;
        }
        $id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';
        if (!$id) {
            return false;
        }
        if ($action === 'delete') {
            return $this->forum->deleteForum($id, currentUser()['id'], true);
        }
        return false;
    }

    public function getComments()
    {
        return $this->forum->getAllComments();
    }

    public function handleCommentAction()
    {
        if (!$this->checkCsrfToken()) {
            return false;
        }
        $id = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);
        if ($id && ($_POST['action'] ?? '') === 'delete') {
            return $this->forum->deleteComment($id, currentUser()['id'], true);
        }
        return false;
    }

    public function getReports()
    {
        return $this->forum->getAllReports();
    }

    public function handleReportAction()
    {
        if (!$this->checkCsrfToken()) {
            return false;
        }
        $id = filter_input(INPUT_POST, 'report_id', FILTER_VALIDATE_INT);
        $action = $_POST['action'] ?? '';
        if ($action === 'resolve' && $id) {
            return $this->forum->resolveReport($id);
        }
        if ($action === 'delete' && $id) {
            return $this->forum->deleteReport($id);
        }
        if ($action === 'delete_post' && $id) {
            $postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
            if ($postId) {
                $this->forum->deleteForum($postId, currentUser()['id'], true);
                return $this->forum->resolveReport($id);
            }
        }
        return false;
    }

    private function checkCsrfToken()
    {
        if (!verifyCsrf()) {
            setFlash('Jeton de sécurité invalide.', 'error');
            return false;
        }
        return true;
    }
}
