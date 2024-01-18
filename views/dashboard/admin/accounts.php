<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="/public/favicon.ico" type="image/x-icon">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/glassEffect.js" defer></script>
    <script src="/js/preload.js"></script>
    <script defer src="/js/pageControls.js"></script>
    <script defer src="/js/searchControls.js"></script>
    <script defer src="/js/userControls.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        
        (new Navbar(NavbarEntry::dashboard))->display();
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::accounts))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <h1>Gestion des comptes</h1>
                <div class="btn-wrapper inline">
                    <a class="btn outline" id="admins-btn" href="/dashboard/admins">Adminstrateurs</a>
                    <a class="btn outline" id="users-btn" href="/dashboard/members">Utilisateurs</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>