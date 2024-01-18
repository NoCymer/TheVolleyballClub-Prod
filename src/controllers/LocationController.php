<?php

namespace Controllers;

use Controllers\AbstractController;
use Exceptions\DisplayableException;
use Models\Location;
use Validation\AddressValidator;

class LocationController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        AddressValidator::checkPostCodeValidity($_POST["post-code-field"]);
    }

    public static function new() {
        try {
            LocationController::validate();
            Location::new(
                $_POST["city-field"],
                $_POST["post-code-field"],
                $_POST["address-field"],
                $_POST["name-field"]
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
        
    }
    
    public static function delete() {
        Location::delete($_POST['id-field']);
    }

    public static function update() {
        try {
            LocationController::validate();

            Location::fetch($_POST["id-field"])
                ->setName($_POST["name-field"])
                ->setPostCode($_POST["post-code-field"])
                ->setCity($_POST["city-field"])
                ->setAddress($_POST["address-field"])
                ->updateDatabase();
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }

    public static function handleRequest(): void {
        switch($_POST["action"]) {
            case 'create':
                LocationController::new();
                break;
            case 'delete':
                LocationController::delete();
                break;
            case 'update':
                LocationController::update();
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
        else if (isset($_GET["redirect"])) {
            header('Location: '.$_GET["redirect"]);
        }
        else header('Location: /');
    }
}

LocationController::handleRequest();
LocationController::redirect();


/**
 * Fields -> [
 *  "city" : string
 *  "post-code" : string
 *  "address" : string
 *  "name" : string
 * ]
 */