<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../Controller/UserController.php';

$controller = new UserController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$account = $id ? $controller->getUserById($id) : null;
if (!$account || (int) $account['id'] === (int) currentUser()['id']) {
    setFlash('Suppression refusée.', 'error');
    header('Location: afficherusers.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf()) {
        setFlash('Jeton de sécurité invalide.', 'error');
    } elseif ($controller->deleteUser($account['id'])) {
        setFlash('Compte supprimé.');
        header('Location: afficherusers.php');
        exit;
    } else {
        setFlash('Suppression impossible.', 'error');
    }
}

$title = 'ANATOMIA - Supprimer un utilisateur';
$currentPage = 'users';
ob_start();
?>
<div class="card">
    <div class="card-body">
        <h3>Supprimer <?= e($account['prenom'] . ' ' . $account['nom']) ?> ?</h3>
        <p><?= e($account['email']) ?> — <?= e($account['role']) ?></p>
        <form method="post" onsubmit="return confirm('Confirmer la suppression du compte ?');">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int) $account['id'] ?>">
            <button class="btn btn-danger" type="submit">Supprimer</button>
            <a class="btn btn-secondary" href="afficherusers.php">Annuler</a>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
