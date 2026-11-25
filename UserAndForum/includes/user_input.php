<?php

require_once __DIR__ . '/../Model/User.php';

function readUserInput($allowAdmin)
{
    $errors = [];
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'utilisateur';
    $date = trim($_POST['date_naissance'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $taille = trim($_POST['taille'] ?? '');
    $poids = trim($_POST['poids'] ?? '');
    $specialite = trim($_POST['specialite'] ?? '');
    $passwordRequired = !empty($_POST['password_required']);

    if ($nom === '' || mb_strlen($nom) > 100) {
        $errors[] = 'Le nom est obligatoire (100 caractères maximum).';
    }
    if ($prenom === '' || mb_strlen($prenom) > 100) {
        $errors[] = 'Le prénom est obligatoire (100 caractères maximum).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'adresse e-mail n\'est pas valide.';
    }
    $allowedRoles = $allowAdmin ? ['utilisateur', 'medecin', 'admin'] : ['utilisateur', 'medecin'];
    if (!in_array($role, $allowedRoles, true)) {
        $errors[] = 'Le rôle choisi n\'est pas autorisé.';
    }
    if ($passwordRequired || $password !== '' || $confirm !== '') {
        if (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }
        if ($password !== $confirm) {
            $errors[] = 'La confirmation du mot de passe ne correspond pas.';
        }
    }
    if ($date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $errors[] = 'La date de naissance n\'est pas valide.';
    }
    if ($genre !== '' && !in_array($genre, ['femme', 'homme', 'autre'], true)) {
        $errors[] = 'Le genre choisi n\'est pas valide.';
    }
    if ($taille !== '' && (!is_numeric($taille) || $taille < 50 || $taille > 250)) {
        $errors[] = 'La taille doit être un nombre entre 50 et 250 cm.';
    }
    if ($poids !== '' && (!is_numeric($poids) || $poids < 20 || $poids > 300)) {
        $errors[] = 'Le poids doit être un nombre entre 20 et 300 kg.';
    }
    if ($role === 'medecin' && $specialite === '') {
        $errors[] = 'La spécialité est obligatoire pour un médecin.';
    }

    $user = new User(
        isset($_POST['id']) ? (int) $_POST['id'] : null,
        $nom,
        $prenom,
        $email,
        $password,
        $role,
        $date !== '' ? $date : null,
        $genre !== '' ? $genre : null,
        $taille !== '' ? $taille : null,
        $poids !== '' ? $poids : null,
        $specialite !== '' ? $specialite : null
    );

    return [$user, $errors, $password !== ''];
}
