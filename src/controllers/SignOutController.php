<?php

namespace Controllers;


class SignOutController implements IRequestHandler{
    public static function handleRequest(): void {
        session_unset();
    }
    public static function redirect() {
        header('Location: /');
    }
}

SignOutController::handleRequest();
SignOutController::redirect();
