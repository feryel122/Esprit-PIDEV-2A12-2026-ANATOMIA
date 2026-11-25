
<div class="app-content-header">
    <div class="container-fluid">
        <h3>Gestion des commentaires</h3>
        <p>Consulter et modérer les commentaires du forum.</p>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des commentaires</h3>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Auteur</th>
                            <th>Publication</th>
                            <th>Commentaire</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($comments)): ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Aucun commentaire disponible.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?= (int)$comment['id'] ?></td>

                                    <td>
                                        <?= e($comment['author_name']) ?>
                                    </td>

                                    <td>
                                        <?= e($comment['post_title'] ?? 'Publication supprimée') ?>
                                    </td>

                                    <td style="max-width:350px; overflow-wrap:anywhere;">
                                        <?= e($comment['content']) ?>
                                    </td>

                                    <td>
                                        <?= e($comment['created_at']) ?>
                                    </td>

                                    <td>
                                        <form method="POST"
                                              onsubmit="return confirm('Supprimer ce commentaire ?');">

                                            <input type="hidden"
                                                   name="csrf_token"
                                                   value="<?= e(csrfToken()) ?>">

                                            <input type="hidden"
                                                   name="comment_id"
                                                   value="<?= (int)$comment['id'] ?>">

                                            <button type="submit"
                                                    name="action"
                                                    value="delete"
                                                    class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
