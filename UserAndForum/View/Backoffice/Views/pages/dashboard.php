
<div class="app-content-header">
    <div class="container-fluid">
        <h3 class="mb-0">Forum Dashboard</h3>
        <p>Bienvenue dans l'espace d'administration ANATOMIA.</p>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <!-- Total Posts -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3><?= (int)($stats['posts'] ?? 0) ?></h3>
                    <p>Total Posts</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-chat-left-text"></i>
                </div>
            </div>
        </div>

        <!-- Total Comments -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3><?= (int)($stats['comments'] ?? 0) ?></h3>
                    <p>Total Comments</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-chat-dots"></i>
                </div>
            </div>
        </div>

        <!-- Total Likes -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3><?= (int)($stats['likes'] ?? 0) ?></h3>
                    <p>Total Likes</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-heart"></i>
                </div>
            </div>
        </div>

        <!-- Total Reports -->
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3><?= (int)($stats['users'] ?? 0) ?></h3>
                    <p>Utilisateurs</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-secondary">
                <div class="inner">
                    <h3><?= (int)($stats['medecins'] ?? 0) ?></h3>
                    <p>Médecins</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-heart-pulse"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3><?= (int)($stats['reports'] ?? 0) ?></h3>
                    <p>Total Reports</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-flag"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Forum Information -->
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Informations du forum</h3>
        </div>

        <div class="card-body">
            <p>
                Bienvenue dans le tableau de bord du forum ANATOMIA.
            </p>

            <p>
                Cette interface permet de suivre les publications,
                les commentaires, les likes et les signalements.
            </p>
        </div>
    </div>
</div>
