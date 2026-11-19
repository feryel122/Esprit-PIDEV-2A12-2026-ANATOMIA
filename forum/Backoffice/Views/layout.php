
<?php
$title = $title ?? 'ANATOMIA - Administration';
$currentPage = $currentPage ?? 'dashboard';
?>

<?php require_once __DIR__ . '/partials/head.php'; ?>
<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<main class="app-main">

    <?php require_once __DIR__ . '/partials/header.php'; ?>

    <div class="app-content">
        <div class="container-fluid">
            <?= $content ?? '' ?>
        </div>
    </div>

</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
<?php require_once __DIR__ . '/partials/scripts.php'; ?>
