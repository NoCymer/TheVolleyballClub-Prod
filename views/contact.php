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
    <title>Contact</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/contact.css">
    <script src="/js/preload.js"></script>
</head>
<body class="preload">
    <?php
        use Components\Navigation\Navbar\Navbar;
        use Components\Navigation\Navbar\NavbarEntry;
        
        (new Navbar(NavbarEntry::contact))->display();
    ?>
    <div class="contact-page">
        <div class="bento-box glassy">
            <form action="" method="post" class="contact-form">
                <h1>Nous contacter</h1>
                <h2>Informations</h2>
                <div class="form-section">
                    <div class="form-section-field">
                        <label for="surname-field">Votre nom</label>
                        <div class="field">
                            <input
                                type="text"
                                name="surname-field"
                                id="surname-field"
                                placeholder="Votre nom"
                                value=""
                                required
                            >
                        </div>
                    </div>
                    <div class="form-section-field">
                        <label for="name-field">Votre prenom</label>
                        <div class="field">
                            <input
                                type="text"
                                name="name-field"
                                id="name-field"
                                placeholder="Votre prenom"
                                value=""
                                required
                            >
                        </div>
                    </div>
                    <div class="form-section-field">
                        <label for="email-field">Votre email</label>
                        <div class="field">
                            <input
                                type="text"
                                name="email-field"
                                id="email-field"
                                placeholder="Votre email"
                                value=""
                                required
                            >
                        </div>
                    </div>
                </div>
                <h2>Message</h2>
                <div class="form-section">
                    <div class="form-section-field">
                        <label for="email-field">Votre message</label>
                        <div class="field" id="message-field">
                            <textarea
                                name="message-field"
                                placeholder="Votre message"
                                value=""
                                required
                            ></textarea>
                        </div>
                    </div>
                </div>

                <div class="btn-wrapper" id="send-action-wrapper">
                    <button class="btn filled" id="edit-btn" type="submit" form="edit">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>