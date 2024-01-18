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
    <title>Planning</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/planning.css">
    <script src="/js/preload.js"></script>
    <script src="/js/glassEffect.js" defer></script>
    <script src="/js/inlineScroller.js" defer></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Models\Competition;
        use Models\Location;
        use Models\User;

        (new Navbar(NavbarEntry::planning))->display();

        $competition = Competition::fetch($_GET['competition_id']);
        $location = Location::fetch($competition->getLocationId());
        $user = isset($_SESSION['userConnect']) ? User::fetch($_SESSION['userConnect']) : null;

        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            'Europe/Paris'
        );
        $competition->hasExpired();

        // Formats the competition date to dd/MM/yyyy
        $date_str = $formatter->format($competition->getStartDateTime());

        $s_date_str = date("H:i", $competition->getStartDateTime());
        $e_date_str = date("H:i", $competition->getEndDateTime());
    ?>

    <div class="planning-page">
        <div class="planning-section left">
            <span class="top-bar"></span>
            <h1>Compétition du <?php echo $date_str?></h1>
            <div class="inline">
                <div class="address">
                    <?php echo get_public_file("symbols/map-point-symbol.svg")?>
                    <div>
                        <h2><?php echo $location->getName()?></h2>
                        <h2><?php echo $location->getAddress()?></h2>
                        <h2><?php echo $location->getPostCode().' '.$location->getCity()?></h2>
                    </div>
                </div>
                <div class="time">
                    <?php echo get_public_file("symbols/clock-symbol.svg")?>
                    <h2><?php echo $s_date_str.' - '.$e_date_str?></h2>
                </div>
            </div>
            <div class="participate">
                <h2><?php echo $competition->getParticipantsCount()?> Participants - <?php echo (($competition->getPlacesLeft() <= 0) ? "Aucune" : $competition->getPlacesLeft()) ?> Place(s) restantes </h2>
                <?php if(isset($_SESSION['userConnect'])) : ?>
                    <?php if(!$competition->hasExpired() && !$user->isParticipatingToCompetition($competition->getId()) && $competition->getPlacesLeft()>0): ?>
                        <form action="/planning/participate" method="post">
                            <input type="hidden" name="type" value="competition">
                            <input type="hidden" name="id" value="<?php echo $competition->getId()?>">
                        <input type="hidden" name="action" value="participate">
                            <button type="sumbit" class="btn filled">Je participe</button>
                        </form>
                    <?php elseif (!$competition->hasExpired() && $user->isParticipatingToCompetition($competition->getId())): ?>
                        <form action="/planning/participate" method="post">
                            <input type="hidden" name="type" value="competition">
                            <input type="hidden" name="id" value="<?php echo $competition->getId()?>">
                            <input type="hidden" name="action" value="stop_participate">
                            <button type="sumbit" class="btn filled">Se desinscrire</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>