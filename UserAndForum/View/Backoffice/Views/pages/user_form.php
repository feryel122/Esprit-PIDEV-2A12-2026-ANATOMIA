<div class="app-content-header">
    <div class="container-fluid">
        <h3><?= e($formTitle) ?></h3>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endforeach; ?>
        <form method="post" class="row g-3">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <?php if (!empty($account['id'])): ?>
                <input type="hidden" name="id" value="<?= (int) $account['id'] ?>">
            <?php else: ?>
                <input type="hidden" name="password_required" value="1">
            <?php endif; ?>
            <div class="col-md-6">
                <label class="form-label">Prénom</label>
                <input class="form-control" name="prenom" required maxlength="100" value="<?= e($account['prenom'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nom</label>
                <input class="form-control" name="nom" required maxlength="100" value="<?= e($account['nom'] ?? '') ?>">
            </div>
            <div class="col-12">
                <label class="form-label">E-mail</label>
                <input class="form-control" type="email" name="email" required value="<?= e($account['email'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Mot de passe <?= empty($account['id']) ? '' : '(laisser vide pour conserver)' ?></label>
                <input class="form-control" type="password" name="password" <?= empty($account['id']) ? 'required' : '' ?>>
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmation</label>
                <input class="form-control" type="password" name="confirm_password" <?= empty($account['id']) ? 'required' : '' ?>>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rôle</label>
                <select class="form-select" name="role" id="role">
                    <?php foreach (['utilisateur' => 'Utilisateur', 'medecin' => 'Médecin', 'admin' => 'Administrateur'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($account['role'] ?? 'utilisateur') === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Genre</label>
                <select class="form-select" name="genre">
                    <option value="">—</option>
                    <?php foreach (['femme' => 'Femme', 'homme' => 'Homme', 'autre' => 'Autre'] as $value => $label): ?>
                        <option value="<?= $value ?>" <?= ($account['genre'] ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date de naissance</label>
                <input class="form-control" type="date" name="date_naissance" value="<?= e($account['date_naissance'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Taille (cm)</label>
                <input class="form-control" type="number" step="0.1" name="taille" value="<?= e($account['taille'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Poids (kg)</label>
                <input class="form-control" type="number" step="0.1" name="poids" value="<?= e($account['poids'] ?? '') ?>">
            </div>
            <div class="col-12" id="specialite-field">
                <label class="form-label">Spécialité</label>
                <input class="form-control" name="specialite" maxlength="150" value="<?= e($account['specialite'] ?? '') ?>">
            </div>
            <div class="col-12">
                <button class="btn btn-success" type="submit">Enregistrer</button>
                <a class="btn btn-secondary" href="afficherusers.php">Retour</a>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('role').addEventListener('change', function () {
    document.getElementById('specialite-field').hidden = this.value !== 'medecin';
});
document.getElementById('specialite-field').hidden = document.getElementById('role').value !== 'medecin';
</script>
