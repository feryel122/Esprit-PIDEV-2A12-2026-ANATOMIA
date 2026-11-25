
<div class="app-content-header">
    <div class="container-fluid">
        <h3>Gestion des signalements</h3>
        <p>Consulter et traiter les signalements du forum.</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des signalements</h3>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped align-middle">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Signalé par</th>
                    <th>Publication</th>
                    <th>Raison</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($reports)): ?>

                <tr>
                    <td colspan="7" class="text-center">
                        Aucun signalement disponible.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($reports as $report): ?>

                    <tr>
                        <td><?= (int)$report['report_id'] ?></td>

                        <td>
                            <?= htmlspecialchars($report['reporter_name'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?php if ($report['post_title'] !== null): ?>

                                <strong>
                                    <?= htmlspecialchars($report['post_title'], ENT_QUOTES, 'UTF-8') ?>
                                </strong>

                                <br>

                                <small>
                                    Auteur :
                                    <?= htmlspecialchars($report['post_author'], ENT_QUOTES, 'UTF-8') ?>
                                </small>

                            <?php else: ?>

                                <span class="text-muted">
                                    Publication supprimée
                                </span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($report['reason'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($report['report_date'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <td>
                            <?php if ($report['report_status'] === 'processed'): ?>

                                <span class="badge bg-success">
                                    Traité
                                </span>

                            <?php else: ?>

                                <span class="badge bg-warning">
                                    En attente
                                </span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex flex-wrap gap-2">

                                <?php if ($report['report_status'] !== 'processed'): ?>

                                    <form method="POST" action="reports.php">

                                        <input type="hidden"
                                               name="csrf_token"
                                               value="<?= e(csrfToken()) ?>">

                                        <input type="hidden"
                                               name="report_id"
                                               value="<?= (int)$report['report_id'] ?>">

                                        <button type="submit"
                                                name="action"
                                                value="resolve"
                                                class="btn btn-success btn-sm">
                                            <i class="bi bi-check-circle"></i>
                                            Traiter
                                        </button>

                                    </form>

                                <?php endif; ?>

                                <?php if ($report['post_id'] !== null): ?>

                                    <form method="POST"
                                          action="reports.php"
                                          onsubmit="return confirm('Supprimer cette publication ?');">

                                        <input type="hidden"
                                               name="csrf_token"
                                               value="<?= e(csrfToken()) ?>">

                                        <input type="hidden"
                                               name="report_id"
                                               value="<?= (int)$report['report_id'] ?>">

                                        <input type="hidden"
                                               name="post_id"
                                               value="<?= (int)$report['post_id'] ?>">

                                        <button type="submit"
                                                name="action"
                                                value="delete_post"
                                                class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                            Supprimer post
                                        </button>

                                    </form>

                                <?php endif; ?>

                                <form method="POST"
                                      action="reports.php"
                                      onsubmit="return confirm('Supprimer ce signalement ?');">

                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?= e(csrfToken()) ?>">

                                    <input type="hidden"
                                           name="report_id"
                                           value="<?= (int)$report['report_id'] ?>">

                                    <button type="submit"
                                            name="action"
                                            value="delete"
                                            class="btn btn-secondary btn-sm">
                                        Supprimer report
                                    </button>

                                </form>

                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
