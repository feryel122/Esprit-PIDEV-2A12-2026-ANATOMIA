<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../Controller/DashboardController.php';

$controller = new DashboardController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->handleReportAction()) {
        setFlash('Signalement mis à jour.');
    }
    header('Location: reports.php');
    exit;
}

$reports = $controller->getReports();
$title = 'ANATOMIA - Signalements';
$currentPage = 'reports';
ob_start();
require __DIR__ . '/../Views/pages/reports.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
