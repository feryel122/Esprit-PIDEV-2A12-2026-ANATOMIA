<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../includes/user_input.php';
require_once __DIR__ . '/../../Controller/UserController.php';

if (isLoggedIn()) {
    header('Location: profil.php');
    exit;
}

$controller = new UserController();
$errors = [];
$old = [
    'nom' => '', 'prenom' => '', 'email' => '', 'role' => 'utilisateur',
    'date_naissance' => '', 'genre' => '', 'taille' => '', 'poids' => '', 'specialite' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'La session a expiré. Rechargez la page.';
    } else {
        $_POST['password_required'] = '1';
        if (($_POST['role'] ?? '') === 'admin') {
            $_POST['role'] = 'utilisateur';
        }
        [$user, $errors] = readUserInput(false);
        $old = $_POST;
        if (!$errors) {
            if ($controller->findByEmail($user->getEmail())) {
                $errors[] = 'Cet e-mail est déjà utilisé.';
            } elseif ($controller->addUser($user, false)) {
                setFlash('Compte créé. Vous pouvez vous connecter.');
                header('Location: login.php');
                exit;
            } else {
                $errors[] = 'Impossible de créer le compte.';
            }
        }
    }
}

$pageTitle = 'Inscription - ANATOMIA';
$currentPage = 'inscription';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <div class="section-heading">
        <h2>Créer un compte</h2>
        <p>Choisissez un compte membre ou médecin. Le rôle administrateur n'est pas disponible ici.</p>
    </div>
    <section class="auth-card">
        <?php foreach ($errors as $error): ?>
            <p class="field-error"><?= e($error) ?></p>
        <?php endforeach; ?>
        <form method="post" class="create-post" style="box-shadow:none;padding:0;margin:0;">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="password_required" value="1">
            <div class="form-grid">
                <input type="text" name="prenom" placeholder="Prénom" maxlength="100" required value="<?= e($old['prenom'] ?? '') ?>">
                <input type="text" name="nom" placeholder="Nom" maxlength="100" required value="<?= e($old['nom'] ?? '') ?>">
                <input class="full" type="email" name="email" placeholder="E-mail" required value="<?= e($old['email'] ?? '') ?>">
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
                <select name="role" id="role">
                    <option value="utilisateur" <?= ($old['role'] ?? '') === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="medecin" <?= ($old['role'] ?? '') === 'medecin' ? 'selected' : '' ?>>Médecin</option>
                </select>
                <select name="genre">
                    <option value="">Genre</option>
                    <option value="femme" <?= ($old['genre'] ?? '') === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="homme" <?= ($old['genre'] ?? '') === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="autre" <?= ($old['genre'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>
                <input type="date" name="date_naissance" value="<?= e($old['date_naissance'] ?? '') ?>">
                <input type="number" step="0.1" name="taille" placeholder="Taille (cm)" value="<?= e($old['taille'] ?? '') ?>">
                <input type="number" step="0.1" name="poids" placeholder="Poids (kg)" value="<?= e($old['poids'] ?? '') ?>">
                <input class="full" id="specialite-field" type="text" name="specialite" placeholder="Spécialité médicale" maxlength="150" value="<?= e($old['specialite'] ?? '') ?>">
            </div>
            <button type="submit" class="publish-button">Créer mon compte</button>
        </form>
        <p>Déjà inscrit ? <a href="login.php">Connexion</a></p>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
