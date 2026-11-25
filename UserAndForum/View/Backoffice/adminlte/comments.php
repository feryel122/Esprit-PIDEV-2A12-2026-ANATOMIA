<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->handleCommentAction()) {
        setFlash('Commentaire supprimé.');
    }
    header('Location: comments.php');
    exit;
}

$comments = $controller->getComments();
$title = 'ANATOMIA - Commentaires';
$currentPage = 'comments';
ob_start();
require __DIR__ . '/../Views/pages/comments.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
