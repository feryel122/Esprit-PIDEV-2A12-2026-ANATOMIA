<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf()
{
    $token = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

function currentUser()
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn()
{
    return is_array(currentUser());
}

function userRole()
{
    $user = currentUser();
    return $user['role'] ?? '';
}

function fullName($user)
{
    return trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
}

function setFlash($message, $type = 'success')
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function pullFlash()
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function refreshSessionUser(array $user)
{
    unset($user['password']);
    $_SESSION['user'] = $user;
}

function requireLogin($loginUrl)
{
    if (!isLoggedIn()) {
        setFlash('Connectez-vous pour continuer.', 'error');
        header('Location: ' . $loginUrl);
        exit;
    }
}

function requireRole(array $roles, $deniedUrl)
{
    if (!isLoggedIn() || !in_array(userRole(), $roles, true)) {
        setFlash('Vous n\'avez pas l\'autorisation d\'accéder à cette page.', 'error');
        header('Location: ' . $deniedUrl);
        exit;
    }
}
