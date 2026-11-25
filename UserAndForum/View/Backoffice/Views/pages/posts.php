
<?php

// Récupération des publications envoyées par le contrôleur
$posts = $posts ?? [];

// Recherche
$search = $search ?? '';

?>

<div class="container-fluid">

    <!-- Titre -->
    <div class="row mb-3">

        <div class="col-sm-6">
            <h3>Gestion des publications</h3>
        </div>

    </div>

    <!-- Recherche -->
    <div class="card">

        <div class="card-header">
            <h3 class="card-title">Rechercher une publication</h3>
        </div>

        <div class="card-body">

            <form method="GET" action="posts.php">

                <div class="input-group">

                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Rechercher..."
                        value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                        Rechercher
                    </button>

                    <a href="posts.php" class="btn btn-secondary">
                        <i class="fas fa-redo"></i>
                        Réinitialiser
                    </a>

                </div>

            </form>

        </div>

    </div>

    <!-- Tableau des publications -->
    <div class="card mt-3">

        <div class="card-header">
            <h3 class="card-title">Liste des publications</h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Contenu</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($posts)): ?>

                            <?php foreach ($posts as $post): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)($post['id'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)($post['title'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)($post['content'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            (string)($post['created_at'] ?? ''),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td>

                                        <form
                                            method="POST"
                                            action="posts.php"
                                            onsubmit="return confirm('Voulez-vous supprimer cette publication ?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="post_id"
                                                value="<?= htmlspecialchars(
                                                    (string)($post['id'] ?? ''),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                            >
                                                <i class="fas fa-trash"></i>
                                                Supprimer
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5" class="text-center">
                                    Aucune publication trouvée.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
