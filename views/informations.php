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
    <title>Informations</title>
    <link rel="stylesheet" href="/css/info-page.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/preload.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;

        (new Navbar(NavbarEntry::informations))->display();
    ?>

    <div class="info-page">
        <div class="image-with-text">
            <div class="img-wrapper">
                <img src="public/4.jpg" alt="">
            </div>
            <h1>Notre Histoire</h1>
        </div>

        <div class="text-image-side">
            <img src="public/logo.png" alt="">
            <div class="text">
                <h1>Qui sommes nous ?</h1>

                <p>"The Volleyball Club", fondé en 2001 à Saint-Dié-des-Vosges, est né de l'amitié et de la passion commune pour le volleyball de Diego et Lobsang. Leur histoire commence à l'université, où ils se sont rencontrés lors d'un tournoi de volleyball inter-facultés. Diego, originaire de Saint-Dié-des-Vosges, était connu pour sa détente impressionnante et son esprit stratégique, tandis que Lobsang, un étudiant international venu du Tibet, se distinguait par sa technique impeccable et sa sérénité sur le terrain.</p>

                <p>Ils ont rapidement formé un duo inséparable, remportant plusieurs compétitions universitaires. Après leurs études, Diego et Lobsang ont réalisé qu'il n'y avait pas de club de volleyball dédié dans leur ville. Animés par le désir de partager leur amour pour ce sport et de créer une communauté autour du volleyball, ils ont décidé de fonder "The Volleyball Club".</p>

                <p>Leur début fut modeste, avec seulement quelques membres et un petit terrain local. Cependant, grâce à leur dévouement et à leur capacité à inspirer les autres, le club a commencé à grandir. Ils ont organisé des tournois locaux, des séances d'entraînement pour les jeunes, et ont même lancé des programmes de volleyball de plage en été.</p>

                <p>En 2005, le club a connu un tournant majeur. Il a été reconnu par la Fédération Française de Volleyball, permettant à ses équipes de participer à des compétitions régionales et nationales. Cette reconnaissance a attiré de nouveaux talents et des sponsors, contribuant à la croissance et au professionnalisme du club.</p>

                <p>Les années suivantes ont été marquées par des succès et des défis. L'équipe masculine a atteint les demi-finales du championnat national en 2010, et l'équipe féminine a gagné le tournoi régional en 2012. Malgré ces succès, le club a dû faire face à des difficultés financières et à des changements de personnel.</p>

                <p>Aujourd'hui, "The Volleyball Club" est un symbole de persévérance, de passion et de communauté à Saint-Dié-des-Vosges. Avec des équipes dans diverses catégories d'âge et des programmes communautaires, le club continue de faire vivre l'héritage de Diego et Lobsang, en inspirant les nouvelles générations à poursuivre leurs rêves sur le terrain de volleyball.</p>
            </div>
        </div>

        <div class="image-with-text left">
            <div class="img-wrapper">
                <img src="public/2.jpg" alt="">
            </div>
            <h1>Legal</h1>
        </div>

        <div class="text-image-side left">
            <img src="public/logo.png" alt="">
            <div class="text">
                <h1>Politique de confidentialité</h1>
                <p>Si vous désirez en apprendre plus sur notre politique de confidentialité vous pouvez cliquer <a href="/privacy">ici</a>.</p>
                <h1>Conditions générales d'utilisation</h1>
                <p>Si vous désirez en apprendre plus sur les conditions générales d'utilisation du site vous pouvez cliquer <a href="/eula">ici</a>.</p>
            </div>
        </div>
    </div>
</body>
</html>