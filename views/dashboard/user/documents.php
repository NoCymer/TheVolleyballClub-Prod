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
        use Components\Navigation\Dashboard\UserNavbar;
        use Components\Navigation\Dashboard\UserNavbarEntry;
        use Models\IdCard;
        use Models\MedicalCertificate;

        (new Navbar(NavbarEntry::dashboard))->display();

        if(isset($_SESSION['error'])) {
            echo("<script>setTimeout(() => alert(`".$_SESSION['error']."`),500);</script>");
            unset($_SESSION['error']);
        }
    ?>
    <div class="dashboard-wrapper">
        <?php (new UserNavbar(UserNavbarEntry::documents))->display(); ?>
        <div class="bento-box glassy dashboard-box">
            <div class="dashboard">
                <?php
                    use Models\User;
                    $user = User::fetch($_SESSION['userConnect']);
                    $medical_certificate = $user->getMedicalCertificateId() ? MedicalCertificate::fetch($user->getMedicalCertificateId()) : null;
                    $id_card = $user->getIdCardId() ? IdCard::fetch($user->getIdCardId()) : null;
                    $user_id = $_SESSION['userConnect'];
                ?>
                <h1>Mes documents</h1>
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
                                    <form action="/dashboard/documents/submit" class="file-form" method="post" id="submit-medical-certificate" enctype="multipart/form-data">
                                        <?php 
                                            
                                            if($medical_certificate == null) {
                                                echo ("
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"action\" value=\"upload-medical-certificate\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"redirect-success\" value=\"/dashboard/documents/\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"redirect-error\" value=\"/dashboard/documents/\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"id-field\" value=\"$user_id\">
                                                    <label form=\"submit-medical-certificate\" class=\"btn upload filled\" for=\"add-medical-certificate-btn\">
                                                        <label form=\"submit-medical-certificate\" for=\"add-medical-certificate-btn\">Ajouter</label>
                                                        <input form=\"submit-medical-certificate\" name=\"medical-certificate-field\" type=\"file\"id=\"add-medical-certificate-btn\" accept=\"image/png, image/jpg, image/jpeg, application/pdf\" required></input>
                                                    </label>
                                                ");
                                            } 
                                            else if (!$medical_certificate->getIsValid()) {
                                                echo ("
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"action\" value=\"upload-medical-certificate\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"redirect-success\" value=\"/dashboard/documents/\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"redirect-error\" value=\"/dashboard/documents/\">
                                                    <input form=\"submit-medical-certificate\" type=\"hidden\" name=\"id-field\" value=\"$user_id\">
                                                    <label form=\"submit-medical-certificate\" class=\"btn upload filled\" for=\"add-medical-certificate-btn\">
                                                        <label form=\"submit-medical-certificate\" for=\"add-medical-certificate-btn\">Modifier</label>
                                                        <input form=\"submit-medical-certificate\" name=\"medical-certificate-field\" type=\"file\"id=\"add-medical-certificate-btn\" accept=\"image/png, image/jpg, image/jpeg, application/pdf\" required></input>
                                                    </label>
                                                ");
                                            }

                                        ?>
                                    </form>
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
                                    <form action="/dashboard/documents/submit" class="file-form" method="post" id="submit-id-card" enctype="multipart/form-data">

                                    <?php 
                                        
                                        if($id_card == null) {
                                            echo ("
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"action\" value=\"upload-id-card\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"redirect-success\" value=\"/dashboard/documents/\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"redirect-error\" value=\"/dashboard/documents/\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"id-field\" value=\"$user_id\">
                                                <label form=\"submit-id-card\" class=\"btn upload filled\" for=\"add-id-card-btn\">
                                                    <label form=\"submit-id-card\" for=\"add-id-card-btn\">Ajouter</label>
                                                    <input form=\"submit-id-card\" name=\"id-card-field\" type=\"file\"id=\"add-id-card-btn\" accept=\"image/png, image/jpg, image/jpeg, application/pdf\" required></input>
                                                </label>
                                            ");
                                        } 
                                        else if (!$id_card->getIsValid()) {
                                            echo ("
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"action\" value=\"upload-id-card\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"redirect-success\" value=\"/dashboard/documents/\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"redirect-error\" value=\"/dashboard/documents/\">
                                                <input form=\"submit-id-card\" type=\"hidden\" name=\"id-field\" value=\"$user_id\">
                                                <label form=\"submit-id-card\" class=\"btn upload filled\" for=\"add-id-card-btn\">
                                                    <label form=\"submit-id-card\" for=\"add-id-card-btn\">Modifier</label>
                                                    <input form=\"submit-id-card\" name=\"id-card-field\" type=\"file\"id=\"add-id-card-btn\" accept=\"image/png, image/jpg, image/jpeg, application/pdf\" required></input>
                                                </label>
                                            ");
                                        }

                                    ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>