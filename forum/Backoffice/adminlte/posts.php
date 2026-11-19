
<?php

session_start();

require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();

// Générer le token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traiter les actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handlePostAction();

    header('Location: posts.php');
    exit;
}

// Récupérer les publications
$posts = $controller->getPosts();

$title = 'ANATOMIA - Gestion des publications';
$currentPage = 'posts';

// Charger le contenu de la page
ob_start();
require __DIR__ . '/../Views/pages/posts.php';
$content = ob_get_clean();

// Afficher le layout AdminLTE
require __DIR__ . '/../Views/layout.php';
