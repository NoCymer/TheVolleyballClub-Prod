<?php

namespace Controllers;

use Authorization\Authenticator;
use Authorization\AuthorizationLevel;

class ResetPasswordController implements IRequestHandler{
    public static function handleRequest(): void {
        $account = Authenticator::accountExists($_POST['email-field']);
        if(!$account) {
            $_SESSION["error"] = "Aucun compte n'est lié à cette adresse mail";
            return;
        }
        switch($account["authLevel"]) {
            case AuthorizationLevel::User:
                break;
            case AuthorizationLevel::Admin:
                break;
        }
    }
    public static function redirect() {
        if(isset($_SESSION["error"])) header('Location: /connection/forgot-password');
        else header('Location: /connection/forgot-password');
    }
}

ResetPasswordController::handleRequest();
ResetPasswordController::redirect();