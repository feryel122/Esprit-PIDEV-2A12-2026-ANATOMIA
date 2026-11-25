<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../includes/user_input.php';
require_once __DIR__ . '/../../../Controller/UserController.php';

$controller = new UserController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$account = $id ? $controller->getUserById($id) : null;
if (!$account) {
    setFlash('Utilisateur introuvable.', 'error');
    header('Location: afficherusers.php');
    exit;
}

$errors = [];
$formTitle = 'Modifier un utilisateur';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'Jeton de sécurité invalide.';
    } else {
        $_POST['id'] = $account['id'];
        if ((int) $account['id'] === (int) currentUser()['id']) {
            $_POST['role'] = 'admin';
        }
        [$user, $errors, $changePassword] = readUserInput(true);
        if (!$errors && $controller->emailTakenByOther($user->getEmail(), $account['id'])) {
            $errors[] = 'Cet e-mail est déjà utilisé.';
        }
        if (!$errors && $controller->updateUser($user, $changePassword)) {
            if ((int) $account['id'] === (int) currentUser()['id']) {
                refreshSessionUser($controller->getUserById($account['id']));
            }
            setFlash('Utilisateur modifié.');
            header('Location: afficheruser.php?id=' . (int) $account['id']);
            exit;
        } elseif (!$errors) {
            $errors[] = 'Modification impossible.';
        }
        $account = array_merge($account, $_POST);
    }
}

$title = 'ANATOMIA - Modifier un utilisateur';
$currentPage = 'users';
ob_start();
require __DIR__ . '/../Views/pages/user_form.php';
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
