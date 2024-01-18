<?php

namespace Controllers;

use Authorization\Authenticator;
use Authorization\AuthorizationLevel;
use Models\Admin;
use Exceptions\DisplayableException;
use Cryptography\PasswordManager;
use Controllers\AbstractController;
use Exceptions\InvalidPasswordException;
use Validation\AccountValidator;

class AdminController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        return true;
    }

    public static function new() {
        try {
            AccountValidator::checkEmailUnicity($_POST['login-field']);
            AccountValidator::checkEmailFormat($_POST['login-field']);
            AccountValidator::checkPasswordStrength($_POST['password-field']);
            AdminController::validate();
        
            // Creates a new admin in the database
            $id = Admin::new(
                $_POST['login-field'],
                PasswordManager::hash($_POST['password-field']),
            );
    
            // Connects the newly created admin
            $_SESSION['adminConnect'] = $id; 
    
            // Redirects to the dashboard
            header('Location: /dashboard');
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
            header('Location: /sign-up');
        }
    }
    
    public static function delete() {
        Admin::delete($_POST['id-field']);
    }

    public static function update() {
        try {
            $admin = Admin::fetch($_POST['id-field']);
            AccountValidator::checkEmailFormat($_POST['login-field']);
            AccountValidator::checkEmailUnicity($_POST['login-field'], $admin->getLogin());
            
            $admin
                ->setLogin($_POST['login-field'])
                ->updateDatabase();
                
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;

            // TODO : Change this location to point to admin edit page
            header('Location: /sign-up');
        }
    }

    public static function changePassword() {
        try {
            $admin = Admin::fetch($_POST['id-field']);
            
            switch(Authenticator::authenticate($admin->getLogin(), PasswordManager::hash($_POST['old-password-field']))) {
                case AuthorizationLevel::Admin:
                    break;
                default:
                    throw new InvalidPasswordException();
            }

            AccountValidator::checkPasswordStrength($_POST['password-field']);
            
            $admin
                ->changePassword(PasswordManager::hash($_POST['password-field']));
                
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }
    public static function handleRequest(): void {
        switch($_POST["action"]) {
            case 'create':
                AdminController::new();
                break;
            case 'delete':
                // Delete only if the admin the deletion is the one connected
                if(!($_SESSION['adminConnect'] == $_POST['id-field']))  {
                    header("Location: /"); 
                } AdminController::delete();
        
                break;
            case 'update':
                // Edit only if the admin requesting the edition is the one connected
                if(!($_SESSION['adminConnect'] == $_POST['id-field']))  {
                    header("Location: /"); 
                } AdminController::update();
        
                break;
            case 'change-password':
                // Edit only if the admin requesting the edition is the one connected
                if(!($_SESSION['adminConnect'] == $_POST['id-field']))  {
                    header("Location: /"); 
                } AdminController::changePassword();
                break;

        }
    }
    public static function redirect() {
        if(isset($_SESSION["error"])) {
            if(isset($_POST["redirect-error"])) {
                header('Location: '.$_POST["redirect-error"]);
            } else {
                header('Location: /');
            }
        }
        else if (isset($_POST["redirect-delete"]) && $_POST["action"]=='delete') {
            header('Location: '.$_POST["redirect-delete"]);
        }
        else if (isset($_POST["redirect-success"])) {
            header('Location: '.$_POST["redirect-success"]);
        }
        else header('Location: /');
    }
}

AdminController::handleRequest();
AdminController::redirect();