<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../Controller/UserController.php';

$controller = new UserController();
$search = trim($_GET['q'] ?? '');
$users = $search === '' ? $controller->getAllUsers() : $controller->searchUsers($search);
$title = 'ANATOMIA - Utilisateurs';
$currentPage = 'users';
ob_start();
require __DIR__ . '/../Views/pages/users.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
