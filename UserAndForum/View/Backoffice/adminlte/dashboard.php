<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();
$stats = $controller->index();
$title = 'ANATOMIA - Tableau de bord';
$currentPage = 'dashboard';

ob_start();
require __DIR__ . '/../Views/pages/dashboard.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
