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
    <title>Accueil</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/sponsors.css">
    <script src="/js/preload.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Carrousel\Carrousel;
        use Models\Partner;
        use Templates\Template;

        (new Navbar(NavbarEntry::home))->display();
        (new Carrousel(["/public/1.jpg", "/public/2.jpg", "/public/3.jpg"]))->display();

    ?>
    <h1 class="sponsor-header">Nos sponsors</h1>
    <div class="sponsors-shelf">
        <?php
            foreach(Partner::fetchAll() as $sponsor) {
                $template = new Template("sponsor.template.php");
                $template->fill_placeholder("sponsor_logo_blob", base64_encode($sponsor->getLogo()));
                $template->fill_placeholder("sponsor_name", $sponsor->getName());
                $template->fill_placeholder("sponsor_link", $sponsor->getWebpage());
                $template->display();
            }
        ?>
    </div>
</body>
</html>