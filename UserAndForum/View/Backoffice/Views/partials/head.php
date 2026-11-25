
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($title ?? 'ANATOMIA - Administration', ENT_QUOTES, 'UTF-8') ?>
    </title>

    <link rel="stylesheet" href="css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #FBF1E8;
        }

        .app-content-header h3 {
            color: #2B1B17;
            font-weight: bold;
        }

        .small-box {
            border-radius: 12px;
            overflow: hidden;
        }

        .small-box .inner h3 {
            font-size: 32px;
            font-weight: bold;
        }

        .content-wrapper {
            background-color: #FBF1E8;
        }

        .app-sidebar {
            background-color: #2B1B17;
        }

        .app-sidebar .nav-link {
            color: white;
        }

        .brand-link {
            color: white !important;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg">
<div class="app-wrapper">
