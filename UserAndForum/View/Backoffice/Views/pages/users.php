<div class="app-content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div>
            <h3>Utilisateurs</h3>
            <p>Comptes, rôles et recherche.</p>
        </div>
        <a href="ajoutuser.php" class="btn btn-success">Ajouter</a>
    </div>
</div>

<form method="get" class="mb-3 d-flex gap-2">
    <input class="form-control" type="search" name="q" value="<?= e($search) ?>" placeholder="Nom, e-mail, rôle, spécialité">
    <button class="btn btn-primary" type="submit">Rechercher</button>
</form>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Rôle</th>
                    <th>Spécialité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$users): ?>
                <tr><td colspan="6" class="text-center">Aucun utilisateur.</td></tr>
            <?php endif; ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= (int) $user['id'] ?></td>
                    <td><?= e($user['prenom'] . ' ' . $user['nom']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td><?= e($user['role']) ?></td>
                    <td><?= e($user['specialite'] ?? '') ?></td>
                    <td class="d-flex flex-wrap gap-2">
                        <a class="btn btn-info btn-sm" href="afficheruser.php?id=<?= (int) $user['id'] ?>">Voir</a>
                        <a class="btn btn-warning btn-sm" href="modifieruser.php?id=<?= (int) $user['id'] ?>">Modifier</a>
                        <?php if ((int) $user['id'] !== (int) currentUser()['id']): ?>
                            <a class="btn btn-danger btn-sm" href="supprimeruser.php?id=<?= (int) $user['id'] ?>">Supprimer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
