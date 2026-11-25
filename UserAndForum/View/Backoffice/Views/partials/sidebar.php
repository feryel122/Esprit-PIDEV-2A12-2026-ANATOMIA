
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <!-- Logo -->
    <div class="sidebar-brand">
        <a href="dashboard.php" class="brand-link">
            <span class="brand-text fw-light">ANATOMIA</span>
        </a>
    </div>

    <!-- Menu -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="dashboard.php"
                       class="nav-link <?= ($currentPage === 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Posts -->
                <li class="nav-item">
                    <a href="posts.php"
                       class="nav-link <?= ($currentPage === 'posts') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-file-text"></i>
                        <p>Posts</p>
                    </a>
                </li>

                <!-- Comments -->
                <li class="nav-item">
                    <a href="comments.php"
                       class="nav-link <?= ($currentPage === 'comments') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-chat-dots"></i>
                        <p>Comments</p>
                    </a>
                </li>

                <!-- Reports -->
                <li class="nav-item">
                    <a href="reports.php"
                       class="nav-link <?= ($currentPage === 'reports') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-flag"></i>
                        <p>Signalements</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="afficherusers.php"
                       class="nav-link <?= ($currentPage === 'users') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Utilisateurs</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../../Frontoffice/logout.php" class="nav-link">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Déconnexion</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</aside>
