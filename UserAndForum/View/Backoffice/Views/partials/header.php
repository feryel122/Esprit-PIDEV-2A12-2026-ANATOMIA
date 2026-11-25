
<nav class="app-header navbar navbar-expand bg-white">
    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Menu">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text fw-bold">
            ANATOMIA — <?= e(fullName(currentUser() ?? [])) ?>
        </span>

    </div>
</nav>
<?php $flash = pullFlash(); ?>
<?php if ($flash): ?>
    <div class="container-fluid mt-3">
        <div class="alert <?= ($flash['type'] ?? '') === 'error' ? 'alert-danger' : 'alert-success' ?>">
            <?= e($flash['message']) ?>
        </div>
    </div>
<?php endif; ?>
