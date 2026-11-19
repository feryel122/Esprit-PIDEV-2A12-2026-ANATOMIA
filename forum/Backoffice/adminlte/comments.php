
<?php
session_start();

require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();

// Générer le token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traiter la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleCommentAction();

    header('Location: comments.php');
    exit;
}

// Récupérer les commentaires
$comments = $controller->getComments();

$title = 'ANATOMIA - Gestion des commentaires';
$currentPage = 'comments';

// Charger la vue
ob_start();
require __DIR__ . '/../Views/pages/comments.php';
$content = ob_get_clean();

// Afficher le layout commun
require __DIR__ . '/../Views/layout.php';
