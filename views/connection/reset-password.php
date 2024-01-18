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
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/forgot-password.css">
    <script src="/js/preload.js"></script>
    <script src="/js/glassEffect.js" defer></script>
    <script src="/js/forgotPassword.js" defer></script>
</head>
<body class="flex-body preload">
    <?php        
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;

        (new Navbar(NavbarEntry::connection))->display();
        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="forgot-password-form-wrapper">
        <div class="bento-box glassy">
            <div class="forgot-password-form">
                <h1>Réinitialiser votre mot de passe</h1>
                <form action="endpoint.php" method="post" id="reset-password-form">
                    <div class="text-field">
                        <input type="text" name="email-field" id="email-field" placeholder="Email" form="reset-password-form">
                    </div>
                    <button class="btn filled" type="submit" form="reset-password-form" id="reset-btn">Réinitialiser</button>
                    <button class="btn outline" type="submit" form="reset-password-form" id="cancel-btn">Annuler</button>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>