
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
    $controller->handleReportAction();

    header('Location: reports.php');
    exit;
}

// Récupérer les signalements
$reports = $controller->getReports();

$title = 'ANATOMIA - Gestion des signalements';
$currentPage = 'reports';

// Charger la vue
ob_start();
require __DIR__ . '/../Views/pages/reports.php';
$content = ob_get_clean();

// Afficher le layout
require __DIR__ . '/../Views/layout.php';
