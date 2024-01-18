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
    <link rel="stylesheet" href="/css/planning.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/inlineScroller.js" defer></script>
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
        use Components\Navigation\Dashboard\UserNavbar;
        use Components\Navigation\Dashboard\UserNavbarEntry;
        use Models\Competition;
        use Models\User;

        (new Navbar(NavbarEntry::dashboard))->display();

        $user = User::fetch($_SESSION["userConnect"]);
    ?>
    <div class="dashboard-wrapper">
        <?php (new UserNavbar(UserNavbarEntry::competitions))->display(); ?>
        <div class="bento-box glassy dashboard-box">
            <div class="dashboard">
                <div class="planning-page">
                    <div class="planning-section left">
                        <h1>Mes Prochaines Compétitions</h1>
                        <div class="scroller-wrapper">
                            <span class="left-control">
                                <?php echo get_public_file("symbols/arrow-left-symbol.svg"); ?>
                            </span>
                            <div class="scroller-fade fade-right">
                                <ul class="inline-scroller">
                                    <?php
                                        $competitions = Competition::fetchAll();
                                        $has_displayed = false;
                                        foreach ($competitions as $competition) {
                                            if(
                                                $competition->hasPassed() ||
                                                !$user->isParticipatingToCompetition($competition->getId()) ||
                                                !in_array($user->getId(), $competition->getParticipantsIds())
                                            )
                                                continue;

                                            $has_displayed = true;
                                            $s_date_time = new DateTime("@".$competition->getStartDateTime());
                                            $e_date_time = new DateTime("@".$competition->getEndDateTime());
                                            $day = $s_date_time->format('j F');
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
                                        if(!$has_displayed) {
                                            echo "<p>Aucune compétition à venir</p>";
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
            </div>
        </div>
    </div>
</body>
</html>