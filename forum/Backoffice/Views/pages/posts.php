
<div class="app-content-header">
    <div class="container-fluid">
        <h3>Gestion des publications</h3>
        <p>Consulter, modifier et supprimer les publications du forum.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des publications</h3>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Auteur</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($posts)): ?>

                <tr>
                    <td colspan="7" class="text-center">
                        Aucune publication disponible.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($posts as $post): ?>

                    <tr>
                        <td><?= (int)$post['id'] ?></td>

                        <td>
                            <?= htmlspecialchars($post['author_name'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($post['status'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <form method="POST"
                                  action="posts.php"
                                  onsubmit="return confirm('Supprimer cette publication ?');">

                                <input type="hidden"
                                       name="csrf_token"
                                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                                <input type="hidden"
                                       name="post_id"
                                       value="<?= (int)$post['id'] ?>">

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
