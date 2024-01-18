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
    <script defer src="/js/pageControls.js"></script>
    <script defer src="/js/searchControls.js"></script>
    <script defer src="/js/userControls.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        
        (new Navbar(NavbarEntry::dashboard))->display();
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::accounts))->display(); ?>
        <div class="bento-box glassy dashboard-box">
            <div class="dashboard">
                <h1>Membres</h1>
                <div class="search-bar">
                    <h2>Recherche</h2>
                    <div class="search-inline">
                        <div class="search-wrapper">
                            <input id="search-field" type="text" placeholder="Prénom, Nom, License" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>">
                            <span class="search-background"></span>
                        </div>
                        <button id="search-btn" class="btn img-container filled"><img src="/public/symbols/lens-symbol.svg" alt=""></button>
                    </div>
                </div>
                <div class="result-table">
                    <?php
                        use Database\DatabaseConnection;
                        use Templates\Template;

                        $connection = new DatabaseConnection();

                        $DISPLAY_COUNT_PER_PAGE = 6;

                        // Gets the search query if exists
                        $search = "%";
                        if(isset($_GET["search"])) {
                            $search = $search.$_GET["search"];
                        }
                        $search = $search."%";
                        
                        // Gets the total number of entries in the table
                        $stmtCount = $connection->getConnection()->prepare("SELECT COUNT(*) AS CT FROM user WHERE nameUser LIKE ? OR surnameUser LIKE ? OR idUser LIKE ?;");
                        $stmtCount->bindValue(1, $search);
                        $stmtCount->bindValue(2, $search);
                        $stmtCount->bindValue(3, $search);
                        $stmtCount->execute();
                        $rowCt = $stmtCount->fetch()["CT"];
                        $TOTAL_PAGE_CT = ceil($rowCt/$DISPLAY_COUNT_PER_PAGE);


                        
                        // Gets the current page
                        $stmt = $connection->getConnection()->prepare("SELECT * FROM user WHERE nameUser LIKE ? OR surnameUser LIKE ? OR idUser LIKE ? LIMIT $DISPLAY_COUNT_PER_PAGE OFFSET ? ;");
                        $page = 1;
                        if(isset($_GET["page"])) {
                            $page = $_GET["page"];
                        }

                        // Redirects to correct URL if requested page is out of bounds
                        if($page<1) {
                            echo "<script>window.location.href='/dashboard/members/?page=1';</script>";
                        }
                        if($page>$TOTAL_PAGE_CT) {
                            echo "<script>window.location.href='/dashboard/members/?page=$TOTAL_PAGE_CT';</script>";
                        }
                        
                        $offset = (($page-1)*$DISPLAY_COUNT_PER_PAGE);
                        $stmt->bindValue(1, $search);
                        $stmt->bindValue(2, $search);
                        $stmt->bindValue(3, $search);
                        $stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
                        try{
                            $stmt->execute();
                        } catch(Exception $e) {
                            echo $e;
                        }

                        // Displays the current page
                        foreach($stmt->fetchAll() as $res) {
                            $template = new Template("member.template.php");
                            $template->fill_placeholder("user_avatar_blob", base64_encode($res["imageUser"]));
                            $template->fill_placeholder("user_name", $res["nameUser"].' '.$res["surnameUser"]);
                            $template->fill_placeholder("user_id",  $res["idUser"]);
                            $template->display();
                        }
                    ?>
                    <div class="page-control">
                        <?php if($page > 1):?>
                            <a class="prev">Precedent</a>
                        <?php endif?>
                        <span>...</span>
                        <?php if($page < $TOTAL_PAGE_CT):?>
                            <a class="next">Suivant</a>
                        <?php endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>