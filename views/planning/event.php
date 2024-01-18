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
        use Models\Event;

        (new Navbar(NavbarEntry::planning))->display();

        $event = Event::fetch($_GET['event_id']);

        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            'Europe/Paris'
        );
        $event->hasExpired();
        // Formats the event date to dd/MM/yyyy
        $date_str = $formatter->format($event->getStartDateTime());

        $s_date_str = date("H:i", $event->getStartDateTime());
        $e_date_str = date("H:i", $event->getEndDateTime());
    ?>

    <div class="planning-page">
        <div class="planning-section left">
            <span class="top-bar"></span>
            <h1><?php echo $event->getName()?> le <?php echo $date_str?></h1>
            <div class="inline">
                <div class="time">
                    <?php echo get_public_file("symbols/clock-symbol.svg")?>
                    <h2><?php echo $s_date_str.' - '.$e_date_str?></h2>
                </div>
            </div>
            <p><?php echo $event->getDescription()?></p>
        </div>
    </div>
</body>
</html>