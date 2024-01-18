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
    <script src="/js/glassEffect.js" defer></script>
    <script src="/js/preload.js"></script>
    <script defer src="/js/partnerControls.js"></script>
    <script defer src="/js/editControls.js"></script>
    <script defer src="/js/avatarField.js"></script>
</head>
<body class="preload">
    <?php

        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        use Models\Partner;

        (new Navbar(NavbarEntry::dashboard))->display();

        $partner = Partner::fetch($_GET['partner']);

        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::partner))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <div class="entry-infos-wrapper">
                    <form action="/dashboard/partners/edit/submit" method="post" id="edit" enctype="multipart/form-data">
                        <input type="hidden" id="action-field" name="action" value="update">
                        <input type="hidden" name="redirect-success" value="/dashboard/partners/view/?partner=<?php echo htmlspecialchars($partner->getId()) ?>">
                        <input type="hidden" name="redirect-error" value="/dashboard/partners/edit/?partner=<?php echo htmlspecialchars($partner->getId()) ?>">
                        <input type="hidden" name="redirect-delete" value="/dashboard/partners">
                        <input type="hidden" name="id-field" value="<?php echo htmlspecialchars($partner->getId()) ?>">
                        <div class="inline">
                            <div class="form-section">
                                <div class="form-section-field">
                                    <label for="avatar-field">Photo d'identité</label>
                                    <div class="avatar-selector">
                                        <img class="display" src="data:image/png;base64,<?php echo(base64_encode($partner->getLogo())); ?>" alt="">
                                        <label for="logo-field"></label>
                                        <input type="file" name="logo-field" form="edit" id="logo-field" accept="image/png, image/jpg, image/jpeg, image/webp">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Identité</h2>
                        <div class="form-section">
                            <div class="form-section-field">
                                <label for="name-field">Nom du partenaire</label>
                                <div class="field">
                                    <input name="name-field" id="name-field" type="text" value="<?php echo($partner->getName()) ?>">
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="link-field">Lien du site du partenaire</label>
                                <div class="field">
                                    <input name="link-field" id="link-field" type="text" value="<?php echo($partner->getWebpage()) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrapper inline">
                            <button class="btn filled" id="edit-btn" type="submit" form="edit">Valider</button>
                            <a class="btn outline" id="cancel-btn" href="/dashboard/partners/view/?partner=<?php echo(htmlspecialchars($partner->getId())) ?>">Annuler</a>
                        </div>
                        <div class="btn-wrapper inline">
                            <button class="btn filled" id="delete-btn" type="submit">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>