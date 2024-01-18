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
        use Models\Event;
        use Models\Training;

        (new Navbar(NavbarEntry::planning))->display();
        function dateToDayAndMonth($date) {
            $formatter = new IntlDateFormatter(
                'fr_FR',
                IntlDateFormatter::LONG,
                IntlDateFormatter::NONE,
                'Europe/Paris'
            );
            $date_f = $formatter->format($date);
            $date_formatted = substr($date_f,0, strlen($date_f)-4);
            $parts = explode(" ", $date_formatted);
            return $parts[0]." ".ucfirst($parts[1]);
        }
    ?>
    <div class="planning-page">
        <div class="planning-section left">
            <span class="top-bar"></span>
            <h1>Prochaines Compétitions</h1>
            <div class="scroller-wrapper">
                <span class="left-control">
                    <?php echo get_public_file("symbols/arrow-left-symbol.svg"); ?>
                </span>
                <div class="scroller-fade fade-right">
                    <ul class="inline-scroller">
                        <?php
                            $competitions = Competition::fetchAll();
                            foreach ($competitions as $competition) {
                                if($competition->hasPassed())
                                    continue;
                                $s_date_time = new DateTime("@".$competition->getStartDateTime());
                                $e_date_time = new DateTime("@".$competition->getEndDateTime());
                                $day = dateToDayAndMonth($s_date_time);
                                $start_h = $s_date_time->format('H\hi');
                                $end_h = $e_date_time->format('H\hi');
                                $id = $competition->getId();
                                echo "
                                    <li class=\"bento-box glassy\">
                                        <h1>$day</h1>
                                        <h2>$start_h - $end_h</h2>
                                        <a href=\"/planning/view/?competition_id=$id\" class=\"btn outline\">En savoir plus</a>
                                    </li>
                                ";
                            }
                            
                        ?>
                    </ul>
                </div>
                <span class="right-control">
                    <?php echo get_public_file("symbols/arrow-right-symbol.svg"); ?>
                </span>
            </div>
        </div>
        <div class="planning-section right">
            <span class="top-bar"></span>
            <h1>Prochains Entrainements</h1>
            <div class="scroller-wrapper rtl">
                <span class="left-control">
                    <?php echo get_public_file("symbols/arrow-left-symbol.svg"); ?>
                </span>
                <div class="scroller-fade fade-right">
                    <ul class="inline-scroller">
                        <?php
                            $trainings = Training::fetchAll();
                            foreach ($trainings as $training) {
                                if($training->hasPassed())
                                    continue;
                                $s_date_time = new DateTime("@".$training->getStartDateTime());
                                $e_date_time = new DateTime("@".$training->getEndDateTime());
                                $day = dateToDayAndMonth($s_date_time);
                                $start_h = $s_date_time->format('H\hi');
                                $end_h = $e_date_time->format('H\hi');
                                $id = $training->getId();
                                echo "
                                    <li class=\"bento-box glassy\">
                                        <h1>$day</h1>
                                        <h2>$start_h - $end_h</h2>
                                        <a href=\"/planning/view/?training_id=$id\" class=\"btn outline\">En savoir plus</a>
                                    </li>
                                ";
                            }
                            
                        ?>
                    </ul>
                </div>
                <span class="right-control">
                    <?php echo get_public_file("symbols/arrow-right-symbol.svg"); ?>
                </span>
            </div>
        </div>
        <div class="planning-section left">
            <span class="top-bar"></span>
            <h1>Prochains Evénements</h1>
            <div class="scroller-wrapper">
                <span class="left-control">
                    <?php echo get_public_file("symbols/arrow-left-symbol.svg"); ?>
                </span>
                <div class="scroller-fade fade-right">
                    <ul class="inline-scroller">
                        <?php
                            $events = Event::fetchAll();
                            foreach ($events as $event) {
                                if($event->hasPassed())
                                    continue;
                                $s_date_time = new DateTime("@".$event->getStartDateTime());
                                $e_date_time = new DateTime("@".$event->getEndDateTime());
                                $day = dateToDayAndMonth($s_date_time);
                                $start_h = $s_date_time->format('H\hi');
                                $end_h = $e_date_time->format('H\hi');
                                $id = $event->getId();
                                $name = $event->getName();
                                echo "
                                    <li class=\"bento-box glassy\">
                                        <h1>$name</h1>
                                        <h2>$day $start_h - $end_h</h2>
                                        <a href=\"/planning/view/?event_id=$id\" class=\"btn outline\">En savoir plus</a>
                                    </li>
                                ";
                            }
                            
                        ?> 
                    </ul>
                </div>
                <span class="right-control">
                    <?php echo get_public_file("symbols/arrow-right-symbol.svg"); ?>
                </span>
            </div>
        </div>
    </div>
</body>
</html>