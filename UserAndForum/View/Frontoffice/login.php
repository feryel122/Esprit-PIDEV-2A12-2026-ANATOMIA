<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_once __DIR__ . '/../../Controller/UserController.php';

if (isLoggedIn()) {
    header('Location: profil.php');
    exit;
}

$controller = new UserController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        $error = 'La session a expiré. Rechargez la page.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = $controller->authenticate($email, $password);
        if ($user) {
            refreshSessionUser($user);
            setFlash('Connexion réussie.');
            header('Location: ' . ($user['role'] === 'admin' ? '../Backoffice/adminlte/dashboard.php' : 'forum.php'));
            exit;
        }
        $error = 'E-mail ou mot de passe incorrect.';
    }
}

$pageTitle = 'Connexion - ANATOMIA';
$currentPage = 'login';
require __DIR__ . '/includes/head.php';
?>
<body>
<?php require __DIR__ . '/includes/navbar.php'; ?>
<main class="community">
    <?php require __DIR__ . '/includes/flash.php'; ?>
    <div class="section-heading">
        <h2>Connexion</h2>
        <p>Accédez à votre profil et au forum.</p>
    </div>
    <section class="auth-card">
        <?php if ($error !== ''): ?><p class="field-error"><?= e($error) ?></p><?php endif; ?>
        <form method="post" class="create-post" style="box-shadow:none;padding:0;margin:0;">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="publish-button">Se connecter</button>
        </form>
        <p>Pas encore de compte ? <a href="ajoutuser.php">Inscription</a></p>
    </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
<?php require __DIR__ . '/includes/scripts.php'; ?>
