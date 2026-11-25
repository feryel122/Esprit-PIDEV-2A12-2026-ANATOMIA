
<?php

require_once __DIR__ . '/../../../includes/bootstrap.php';

requireRole(['admin'], '../../Frontoffice/login.php');

require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();

/*
|--------------------------------------------------------------------------
| Gestion des actions POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($controller->handlePostAction()) {
        setFlash('Publication retirée du forum.');
    }

    header('Location: posts.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Recherche des publications
|--------------------------------------------------------------------------
*/

$search = trim($_GET['q'] ?? '');

/*
|--------------------------------------------------------------------------
| Récupération des publications
|--------------------------------------------------------------------------
*/

$posts = $controller->getPosts();

if (!is_array($posts)) {
    $posts = [];
}

/*
|--------------------------------------------------------------------------
| Filtrage de la recherche
|--------------------------------------------------------------------------
*/

if ($search !== '') {

    $posts = array_filter($posts, function ($post) use ($search) {

        $title = $post['title'] ?? '';
        $content = $post['content'] ?? '';

        return stripos($title, $search) !== false
            || stripos($content, $search) !== false;

    });

}

/*
|--------------------------------------------------------------------------
| Variables du template AdminLTE
|--------------------------------------------------------------------------
*/

$title = 'ANATOMIA - Publications';

$currentPage = 'posts';

/*
|--------------------------------------------------------------------------
| Affichage de la View
|--------------------------------------------------------------------------
*/

ob_start();

require __DIR__ . '/../Views/pages/posts.php';

$content = ob_get_clean();

/*
|--------------------------------------------------------------------------
| Chargement du layout AdminLTE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../Views/layout.php';
