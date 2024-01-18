<?php

namespace Controllers;

use Cryptography\PasswordManager;
use Authorization\Authenticator;
use Authorization\AuthorizationLevel;

class SignInController implements IRequestHandler{
    public static function handleRequest(): void {
        $auth = Authenticator::authenticate(
            $_POST['email-field'],
            PasswordManager::hash($_POST['password-field'])
        );

        switch($auth) {
            case AuthorizationLevel::User: 
            case AuthorizationLevel::Admin: 
                header('Location: /');
                break;
            case AuthorizationLevel::Guest:
                // Sets the error message
                $_SESSION["error"] = "Email ou mot de passe incorrect";
                // Sends back the form's data to refill the form
                $_SESSION['form-data'] = $_POST;
                 
                break;
        }
    }
    public static function redirect() {
        if(isset($_SESSION["error"])) header('Location: /sign-in');
        else header('Location: /');
    }
}

SignInController::handleRequest();
SignInController::redirect();