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
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/preload.js"></script>
    <script src="/js/glassEffect.js" defer></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        use Components\Navigation\Dashboard\AdminNavbar;
        use Components\Navigation\Dashboard\AdminNavbarEntry;
        use Models\Group;
    use Models\IdCard;
    use Models\MedicalCertificate;
    use Models\User;

        (new Navbar(NavbarEntry::dashboard))->display();

        $user = User::fetch($_GET['user']);
    ?>
    <div class="dashboard-wrapper">
        <?php (new AdminNavbar(AdminNavbarEntry::accounts))->display(); ?>
        <div class="bento-box glassy entry-display-box">
            <div class="entry-display">
                <div class="entry-infos-wrapper">
                    <div class="inline">
                        <img class="entry-img-display" src="data:image/png;base64,<?php echo(base64_encode($user->getImageUser())); ?>" alt="">
                        <div class="ml">
                            <h1 class="big-title"><?php echo($user->getName()." ".$user->getSurname()); ?></h1>
                            <h2 class="big-subtitle"><?php echo($user->getId()); ?></h2>
                        </div>
                    </div>
                    <h2>Identité</h2>
                    <div class="form-section">
                        <div class="form-section-field">
                            <label for="gender-field">Civilité</label>
                            <div class="field">
                                <input type="text" value="<?php echo($user->getGender() == 0 ? "Mme." : "Mr.") ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <label for="name-field">Prenom</label>
                            <div class="field">
                                <input type="text" value="<?php echo($user->getName()) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <label for="surname-field">Nom de famille</label>
                            <div class="field">
                                <input type="text" value="<?php echo($user->getSurname()) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <label for="birthdate-field">Date de naissance</label>
                            <div class="field">
                                <input type="text" value="<?php echo($user->getBirthdate()) ?>" disabled>
                            </div>  
                        </div>
                    </div>
                    <h2>Compte</h2>
                    <div class="form-section">
                        <div class="form-section-field">
                            <label for="group-field">Groupe</label>
                            <div class="field">
                                <input type="text" value="<?php echo(Group::fetch($user->getGroupID())->getName()) ?>" disabled>
                            </div>
                        </div>
                        <div class="form-section-field">
                            <label for="email-field">Email</label>
                            <div class="field">
                                <input type="text" value="<?php echo($user->getEmail()) ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <h2>Documents</h2>
                    <?php
                        $user_id = $_GET['user']; 
                        $user = User::fetch($user_id);
                        $medical_certificate = $user->getMedicalCertificateId() ? MedicalCertificate::fetch($user->getMedicalCertificateId()) : null;
                        $id_card = $user->getIdCardId() ? IdCard::fetch($user->getIdCardId()) : null;
                    ?>
                    <table class="document-table">
                        <thead>
                            <th class="name">Nom du document</th>
                            <th class="document">Document</th>
                            <th class="size">Taille</th>
                            <th class="validation">Validation</th>
                            <th class="actions">Actions</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="name">Certificat Médical</td>
                                <td class="document">
                                    <?php
                                        if($medical_certificate != null)  {
                                            echo ("<a href=\"/dashboard/documents/download/?user=$user_id&document=medicalCertificate\" target=\"_blank\">".$medical_certificate->getFileName()."</a>");
                                        }
                                    ?>
                                </td>
                                <td class="size">
                                    <?php if($medical_certificate != null) echo (strval((round(strlen($medical_certificate->getDocument()) / 1024 * 100))/100)."KB") ?> 
                                </td>
                                <td class="validation">
                                    <?php
                                        if($medical_certificate != null) {
                                            if(is_null($medical_certificate->getIsValid())) {
                                                echo("En attente");
                                            }
                                            else if ($medical_certificate->getIsValid()) {
                                                echo("Validé");
                                            }
                                            else {
                                                echo("Invalidé");
                                            }
                                        }
                                    ?>
                                </td>
                                <td class="actions">
                                    <div class="btn-wrapper inline">
                                        <?php 
                                            if($medical_certificate != null) {
                                                if(is_null($medical_certificate->getIsValid())) {
                                                    echo ("
                                                        <a class=\"btn filled\" id=\"validate-medical-certificate-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=medicalCertificate&action=validate&redirect=/dashboard/members/view&quest;user=$user_id\">Valider</a>
                                                    ");
                                                }
                                                else if(!$medical_certificate->getIsValid()) {
                                                    echo ("
                                                        <a class=\"btn filled\" id=\"validate-medical-certificate-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=medicalCertificate&action=validate&redirect=/dashboard/members/view&quest;user=$user_id\">Valider</a>
                                                    ");
                                                } else {
                                                    echo ("
                                                        <a class=\"btn outline\" id=\"invalidate-id-card-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=medicalCertificate&action=invalidate&redirect=/dashboard/members/view&quest;user=$user_id\">Invalider</a>
                                                    ");
                                                }
                                            }

                                        ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="document-table">
                        <thead>
                            <th class="name">Nom du document</th>
                            <th class="document">Document</th>
                            <th class="size">Taille</th>
                            <th class="validation">Validation</th>
                            <th class="actions">Actions</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="name">Carte d'identitée</td>
                                <td class="document">
                                    <?php
                                        if($id_card != null)  {
                                            echo ("<a href=\"/dashboard/documents/download/?user=$user_id&document=idCard\" target=\"_blank\">".$id_card->getFileName()."</a>");
                                        }
                                    ?>
                                </td>
                                <td class="size">
                                    <?php if($id_card != null) echo (strval((round(strlen($id_card->getDocument()) / 1024) * 100)/100)."KB") ?> 
                                </td>
                                <td class="validation">
                                    <?php
                                        if($id_card != null) {
                                            if(is_null($id_card->getIsValid())) {
                                                echo("En attente");
                                            }
                                            else if ($id_card->getIsValid()) {
                                                echo("Validé");
                                            }
                                            else {
                                                echo("Invalidé");
                                            }
                                        }
                                    ?>
                                </td>
                                <td class="actions">
                                    <div class="btn-wrapper inline">
                                        <?php 
                                            if($id_card != null) {
                                                if(is_null($id_card->getIsValid())) {
                                                    echo ("
                                                        <a class=\"btn filled\" id=\"validate-id-card-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=idCard&action=validate&redirect=/dashboard/members/view&quest;user=$user_id\">Valider</a>
                                                    ");
                                                    echo ("
                                                        <a class=\"btn outline\" id=\"invalidate-id-card-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=idCard&action=invalidate&redirect=/dashboard/members/view&quest;user=$user_id\">Invalider</a>
                                                    ");
                                                }
                                                else if(!$id_card->getIsValid()) {
                                                    echo ("
                                                        <a class=\"btn filled\" id=\"validate-id-card-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=idCard&action=validate&redirect=/dashboard/members/view&quest;user=$user_id\">Valider</a>
                                                    ");
                                                }
                                                else {
                                                    echo ("
                                                        <a class=\"btn outline\" id=\"invalidate-id-card-btn\" href=\"/dashboard/documents/validate/?user=$user_id&document=idCard&action=invalidate&redirect=/dashboard/members/view&quest;user=$user_id\">Invalider</a>
                                                    ");
                                                }
                                            }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="btn-wrapper inline">
                        <a class="btn filled" id="edit-btn" href="/dashboard/members/edit/?user=<?php echo ($user->getId());?>">Editer</a>
                        <a class="btn outline" id="cancel-btn" href="/dashboard/members">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>