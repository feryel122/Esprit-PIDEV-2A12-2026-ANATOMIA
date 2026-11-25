<?php
require_once __DIR__ . '/../../../includes/bootstrap.php';
requireRole(['admin'], '../../Frontoffice/login.php');
require_once __DIR__ . '/../../../Controller/UserController.php';

$controller = new UserController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$account = $id ? $controller->getUserById($id) : null;
if (!$account) {
    setFlash('Utilisateur introuvable.', 'error');
    header('Location: afficherusers.php');
    exit;
}

$title = 'ANATOMIA - Fiche utilisateur';
$currentPage = 'users';
ob_start();
?>
<div class="card">
    <div class="card-header"><h3 class="card-title"><?= e($account['prenom'] . ' ' . $account['nom']) ?></h3></div>
    <div class="card-body">
        <p><strong>E-mail :</strong> <?= e($account['email']) ?></p>
        <p><strong>Rôle :</strong> <?= e($account['role']) ?></p>
        <p><strong>Genre :</strong> <?= e($account['genre'] ?? '—') ?></p>
        <p><strong>Naissance :</strong> <?= e($account['date_naissance'] ?? '—') ?></p>
        <p><strong>Taille :</strong> <?= e($account['taille'] ?? '—') ?> cm</p>
        <p><strong>Poids :</strong> <?= e($account['poids'] ?? '—') ?> kg</p>
        <p><strong>Spécialité :</strong> <?= e($account['specialite'] ?? '—') ?></p>
        <p><strong>Créé le :</strong> <?= e($account['created_at']) ?></p>
        <a class="btn btn-warning" href="modifieruser.php?id=<?= (int) $account['id'] ?>">Modifier</a>
        <a class="btn btn-secondary" href="afficherusers.php">Retour</a>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../Views/layout.php';
