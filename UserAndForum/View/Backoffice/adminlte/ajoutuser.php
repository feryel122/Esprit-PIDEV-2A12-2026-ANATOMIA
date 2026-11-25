<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../includes/user_input.php';
require_once __DIR__ . '/../../../Controller/UserController.php';

$controller = new UserController();
$errors = [];
$account = ['role' => 'utilisateur'];
$formTitle = 'Ajouter un utilisateur';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'Jeton de sécurité invalide.';
    } else {
        $_POST['password_required'] = '1';
        [$user, $errors] = readUserInput(true);
        $account = $_POST;
        if (!$errors && $controller->findByEmail($user->getEmail())) {
            $errors[] = 'Cet e-mail est déjà utilisé.';
        }
        if (!$errors && $controller->addUser($user, true)) {
            setFlash('Utilisateur ajouté.');
            header('Location: afficherusers.php');
            exit;
        } elseif (!$errors) {
            $errors[] = 'Création impossible.';
        }
    }
}

$title = 'ANATOMIA - Ajouter un utilisateur';
$currentPage = 'users';
ob_start();
require __DIR__ . '/../Views/pages/user_form.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
