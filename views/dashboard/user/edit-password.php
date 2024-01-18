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
    <script defer src="/js/userControls.js"></script>
    <script defer src="/js/avatarField.js"></script>
    <script defer src="/js/passwordChangeValidation.js"></script>
</head>
<body class="preload">
    <?php

        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\UserNavbar;
        use Components\Navigation\Dashboard\UserNavbarEntry;
        use Models\User;

        (new Navbar(NavbarEntry::dashboard))->display();

        $user = User::fetch($_SESSION['userConnect']);

        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="dashboard-wrapper">
        <?php (new UserNavbar(UserNavbarEntry::profile))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <div class="entry-infos-wrapper">
                    <form action="/dashboard/profile/edit-password/submit" method="post" id="edit" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="change-password">
                        <input type="hidden" name="redirect-success" value="/dashboard/profile/">
                        <input type="hidden" name="redirect-error" value="/dashboard/profile/edit-password/">
                        <input type="hidden" name="id-field" value="<?php echo $_SESSION['userConnect'] ?>">
                        <h2>Changer de mot de passe</h2>
                        <div class="form-section">
                            <div class="form-section-field">
                                <label for="password-field">Ancien mot de passe</label>
                                <div class="field">
                                    <input
                                        type="password"
                                        name="old-password-field"
                                        id="old-password-field"
                                        placeholder="Ancien mot de passe"
                                        value=""
                                        required
                                    >
                                </div>
                            </div>
                            </div>
                        <h2>Nouveau mot de passe</h2>
                        <div class="form-section">
                            <div class="form-section-field">
                                <label for="password-field">Mot de passe</label>
                                <div class="field">
                                    <input
                                        type="password"
                                        name="password-field"
                                        id="password-field"
                                        placeholder="Mot de passe"
                                        value=""
                                        required
                                    >
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="confirm-password-field">Confirmer mot de passe</label>
                                <div class="field">
                                    <input
                                        type="password"
                                        name="confirm-password-field"
                                        id="confirm-password-field"
                                        placeholder="Mot de passe"
                                        value=""
                                        required
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrapper inline">
                            <button class="btn filled" id="edit-btn" type="submit" form="edit">Enregistrer</button>
                            <a class="btn outline" id="cancel-btn" href="/dashboard/profile">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>