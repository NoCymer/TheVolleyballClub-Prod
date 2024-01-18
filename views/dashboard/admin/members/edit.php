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
    <title>Fiche utilisateur</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/glassEffect.js" defer></script>
    <script src="/js/preload.js"></script>
    <script defer src="/js/userControls.js"></script>
    <script defer src="/js/editControls.js"></script>
    <script defer src="/js/avatarField.js"></script>
</head>
<body class="preload">
    <?php

        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;

        use Models\User;

        (new Navbar(NavbarEntry::dashboard))->display();

        $user = User::fetch($_GET['user']);

        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::accounts))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <div class="entry-infos-wrapper">
                    <form action="/dashboard/members/edit/submit" method="post" id="edit" enctype="multipart/form-data">
                        <input type="hidden" id="action-field" name="action" value="update">
                        <input type="hidden" name="redirect-success" value="/dashboard/members/view/?user=<?php echo htmlspecialchars($user->getId()) ?>">
                        <input type="hidden" name="redirect-error" value="/dashboard/members/edit/?user=<?php echo htmlspecialchars($user->getId()) ?>">
                        <input type="hidden" name="redirect-delete" value="/dashboard/members">
                        <input type="hidden" name="id-field" value="<?php echo htmlspecialchars($user->getId()) ?>">
                        <div class="inline">
                            <div class="form-section">
                                <div class="form-section-field">
                                    <label for="avatar-field">Photo d'identité</label>
                                    <div class="avatar-selector">
                                        <img class="display" src="data:image/png;base64,<?php echo(base64_encode($user->getImageUser())); ?>" alt="">
                                        <label for="avatar-field"></label>
                                        <input type="file" name="avatar-field" form="edit" id="avatar-field" accept="image/png, image/jpg, image/jpeg, image/webp">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h2>Identité</h2>
                        <div class="form-section">
                            <div class="form-section-field">
                                <label for="gender-field">Civilité</label>
                                <div class="field">
                                    <div class="select-field">
                                        <select name="gender-field" id="gender-field" required>
                                            <option disabled selected value>--</option>
                                            <option <?php echo(($user->getGender() == 1) ? "selected" : "") ?> title="" descGroup="" value="1">Mr.</option>
                                            <option <?php echo(($user->getGender() == 0) ? "selected" : "") ?> title="" descGroup="" value="0">Mme.</option>
                                        </select>
                                        <?php echo get_public_file("symbols/arrow-right-symbol.svg") ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="name-field">Prenom</label>
                                <div class="field">
                                    <input name="name-field" id="name-field" type="text" value="<?php echo($user->getName()) ?>">
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="surname-field">Nom de famille</label>
                                <div class="field">
                                    <input name="surname-field" id="surname-field" type="text" value="<?php echo($user->getSurname()) ?>">
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="birthdate-field">Date de naissance</label>
                                <div class="field">
                                    <input
                                        type="date"
                                        name="birthdate-field"
                                        id="birthdate-field"
                                        placeholder=""
                                        value="<?php echo htmlspecialchars($user->getBirthdate())?>"
                                        required
                                    >
                                </div>  
                            </div>
                        </div>
                        <h2>Compte</h2>
                        <div class="form-section">
                            <div class="form-section-field">
                                <label for="group-field">Groupe</label>
                                <div class="field">
                                    <div class="select-field">
                                        <select name="group-field" id="group-field" required>
                                            <option disabled selected value>--------</option>
                                            <?php
                                                use Database\DatabaseConnection;

                                                $connection = new DatabaseConnection();
                                                $stmt = $connection->getConnection()->prepare("SELECT * FROM `group`;");
                                                $stmt->execute();
                                                $res = $stmt->fetchAll();
                                                foreach($res as $cur) {
                                                    echo(
                                                        "<option ".
                                                        (($user->getGroupID() == $cur["idGroup"]) ? "selected" : "").
                                                        " title=".$cur["descGroup"].
                                                        " value=".$cur["idGroup"].">".
                                                        $cur["nameGroup"].
                                                        "</option>"
                                                    );
                                                }
                                            ?>
                                        </select>
                                        <?php echo get_public_file("symbols/arrow-right-symbol.svg") ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-section-field">
                                <label for="email-field">Email</label>
                                <div class="field">
                                    <input name="email-field" id="email-field" type="text" value="<?php echo(htmlspecialchars($user->getEmail())) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrapper inline">
                            <button class="btn filled" id="edit-btn" type="submit" form="edit">Valider</button>
                            <a class="btn outline" id="cancel-btn" href="/dashboard/members/view/?user=<?php echo(htmlspecialchars($user->getId())) ?>">Annuler</a>
                        </div>
                        <div class="btn-wrapper inline">
                            <button class="btn filled" id="delete-btn" type="submit">Supprimer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>