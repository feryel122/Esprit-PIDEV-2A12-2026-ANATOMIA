<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../includes/user_input.php';
require_once __DIR__ . '/../../Controller/UserController.php';

requireLogin('login.php');
$controller = new UserController();
$account = $controller->getUserById(currentUser()['id']);
if (!$account) {
    header('Location: logout.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $errors[] = 'La session a expiré. Rechargez la page.';
    } else {
        $_POST['id'] = $account['id'];
        $_POST['role'] = $account['role'];
        [$user, $errors, $changePassword] = readUserInput(true);
        if (!$errors && $controller->emailTakenByOther($user->getEmail(), $account['id'])) {
            $errors[] = 'Cet e-mail est déjà utilisé.';
        }
        if (!$errors && $controller->updateProfile($user, $changePassword)) {
            refreshSessionUser($controller->getUserById($account['id']));
            setFlash('Profil mis à jour.');
            header('Location: profil.php');
            exit;
        } elseif (!$errors) {
            $errors[] = 'La mise à jour a échoué.';
        }
    }
    $account = array_merge($account, $_POST);
}

$pageTitle = 'Profil - ANATOMIA';
$currentPage = 'profil';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <div class="section-heading">
        <h2>Mon profil</h2>
        <p>
            <?= e($account['role']) ?>
            <?php if ($account['role'] === 'medecin'): ?><span class="badge-role">Médecin</span><?php endif; ?>
        </p>
    </div>
    <section class="profile-card">
        <?php foreach ($errors as $error): ?><p class="field-error"><?= e($error) ?></p><?php endforeach; ?>
        <form method="post" class="create-post" style="box-shadow:none;padding:0;margin:0;">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="form-grid">
                <input type="text" name="prenom" required maxlength="100" value="<?= e($account['prenom']) ?>">
                <input type="text" name="nom" required maxlength="100" value="<?= e($account['nom']) ?>">
                <input class="full" type="email" name="email" required value="<?= e($account['email']) ?>">
                <input type="password" name="password" placeholder="Nouveau mot de passe (optionnel)">
                <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe">
                <input type="date" name="date_naissance" value="<?= e($account['date_naissance'] ?? '') ?>">
                <select name="genre">
                    <option value="">Genre</option>
                    <?php foreach (['femme' => 'Femme', 'homme' => 'Homme', 'autre' => 'Autre'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($account['genre'] ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="0.1" name="taille" placeholder="Taille (cm)" value="<?= e($account['taille'] ?? '') ?>">
                <input type="number" step="0.1" name="poids" placeholder="Poids (kg)" value="<?= e($account['poids'] ?? '') ?>">
                <?php if ($account['role'] === 'medecin'): ?>
                    <input class="full" type="text" name="specialite" placeholder="Spécialité" required maxlength="150" value="<?= e($account['specialite'] ?? '') ?>">
                <?php endif; ?>
            </div>
            <button type="submit">Enregistrer</button>
        </form>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
