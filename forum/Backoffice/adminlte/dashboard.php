
<?php
session_start();

require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();
$stats = $controller->index();

$title = 'ANATOMIA - Forum Dashboard';
$currentPage = 'dashboard';

ob_start();
require __DIR__ . '/../Views/pages/dashboard.php';
$content = ob_get_clean();

require __DIR__ . '/../Views/layout.php';
