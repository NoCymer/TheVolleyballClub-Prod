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
    <title>Fiche utilisateur</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/preload.js"></script>
    <script src="/js/glassEffect.js" defer></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        use Models\Location;

        (new Navbar(NavbarEntry::dashboard))->display();

        $location = Location::fetch($_GET['location']);
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::location))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <div class="entry-infos-wrapper">
                    <h2>Nom du gymnase</h2>
                    <div class="form-section">
                        <div class="form-section-field">
                            <div class="field">
                                <input name="name-field" id="name-field" type="text" value="<?php echo($location->getName()) ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <h2>Addresse du gymnase</h2>
                    <div class="form-section">
                        <div class="form-section-field">
                            <div class="field">
                                <input name="address-field" id="link-field" type="text" value="<?php echo($location->getAddress()) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <div class="field">
                                <input name="post-code-field" id="link-field" type="text" value="<?php echo($location->getPostCode()) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <div class="field">
                                <input name="city-field" id="link-field" type="text" value="<?php echo($location->getCity()) ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="btn-wrapper inline">
                        <a class="btn filled" id="edit-btn" href="/dashboard/locations/edit/?location=<?php echo ($location->getId());?>">Editer</a>
                        <a class="btn outline" id="cancel-btn" href="/dashboard/locations">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>