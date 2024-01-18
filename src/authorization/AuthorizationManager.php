<?php

namespace Authorization;

abstract class AuthorizationManager{

    /**
     * Redirects to $redirect if not logged in as an admin
     * @param string $redirect Url to redirect to
     */
    public static function requireAdmin($redirect = "/"): void {
        if(!AuthorizationManager::isAdmin()) {
            header("Location: ".$redirect); 
            exit;
        }
    }

    /**
     * Redirects to $redirect if not logged in as a user
     * @param string $redirect Url to redirect to
     */
    public static function requireUser($redirect = "/"): void {
        if(!AuthorizationManager::isUser()) {
            header("Location: ".$redirect); 
            exit;
        }
    }

    /**
     * Redirects to $redirect if not logged in as either an admin or a user
     * @param string $redirect Url to redirect to
     */
    public static function requireLoggedIn($redirect = "/"): void {
        if(!AuthorizationManager::isLoggedIn()) {
            header("Location: ".$redirect); 
            exit;
        }
    }

    /**
     * Redirects to $redirect if logged in as either an admin or a user
     * @param string $redirect Url to redirect to
     */
    public static function requireGuest($redirect = "/"): void {
        if(!AuthorizationManager::isGuest()) {
            header("Location: ".$redirect); 
            exit;
        }
    }


    /**
     * Returns true if logged in as an admin, else false 
     */
    public static function isAdmin(): bool {
        return isset($_SESSION['adminConnect']);
    }

    /**
     * Returns true if logged in as an user, else false 
     */
    public static function isUser(): bool {
        return isset($_SESSION['userConnect']);
    }

    /**
     * Returns true if either logged in as an admin or a user, else false 
     */
    public static function isLoggedIn(): bool {
        return (isset($_SESSION['userConnect']) || isset($_SESSION['adminConnect']));
    }

    /**
     * Returns true if either not logged in 
     */
    public static function isGuest(): bool {
        return !AuthorizationManager::isLoggedIn();
    }
}