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
    <script defer src="/js/documentsSend.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        use Models\Competition;
        use Templates\Template;

        (new Navbar(NavbarEntry::dashboard))->display();

        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::competitions))->display(); ?>
        <div class="bento-box glassy dashboard-box">
            <div class="dashboard">
                <h1>Candidatures en attente</h1>
                <?php
                    $competionApplications = array();
                    $competitions = Competition::fetchAll();
                    foreach($competitions as $competition) {
                        $applications = $competition->getAllUnreviewedApplications();
                        // echo("<pre>");
                        // var_dump($applications);
                        // echo("</pre>");
                        foreach($applications as $application) {
                            $entry = array();
                            $entry["competitionId"] = $competition->getId();
                            $entry["userId"] = $application;
                            array_push($competionApplications, $entry);
                        }
                    }
                    foreach($competionApplications as $application) {
                        $template = new Template("competition-validation.template.php");
                        $template->fill_placeholder("application_id_competition", $application["competitionId"]);
                        $template->fill_placeholder("application_id_user", $application["userId"]);
                        $template->display();
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>