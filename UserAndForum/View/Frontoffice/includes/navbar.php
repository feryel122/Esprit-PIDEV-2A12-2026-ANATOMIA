<header class="header">
    <div class="logo">
        <a href="accueil.php">
            <img src="../Assets/images/logo.png" alt="ANATOMIA">
        </a>
    </div>

    <button type="button" class="nav-toggle" aria-label="Ouvrir le menu">Menu</button>

    <nav class="navigation" id="mainNav">
        <a href="accueil.php" class="<?= ($currentPage ?? '') === 'accueil' ? 'active' : '' ?>">Accueil</a>
        <a href="forum.php" class="<?= ($currentPage ?? '') === 'forum' ? 'active' : '' ?>">Forum</a>
        <?php if (isLoggedIn() && userRole() === 'medecin'): ?>
            <a href="medecin.php" class="<?= ($currentPage ?? '') === 'medecin' ? 'active' : '' ?>">Espace médecin</a>
        <?php endif; ?>
        <a href="#" title="Module Recettes — autre membre de l'équipe">Recettes</a>
        <a href="#" title="Module Articles — autre membre de l'équipe">Articles</a>
    </nav>

    <div class="header-icons">
        <?php if (isLoggedIn()): ?>
            <a href="profil.php" aria-label="Profil"><?= e(fullName(currentUser())) ?></a>
            <?php if (userRole() === 'admin'): ?>
                <a href="../Backoffice/adminlte/dashboard.php">Admin</a>
            <?php endif; ?>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="ajoutuser.php">Inscription</a>
        <?php endif; ?>
    </div>
</header>
