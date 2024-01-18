<?php

namespace Controllers;

use Controllers\AbstractController;
use Exceptions\DisplayableException;
use Models\Partner;
use Validation\ImageValidator;

class PartnerController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        return true;
    }

    public static function new() {
        try {
            PartnerController::validate();
            ImageValidator::checkImageType($_FILES["logo-field"]['type']);

            Partner::new(
                $_POST["name-field"],
                file_get_contents($_FILES["logo-field"]["tmp_name"]),
                $_POST["link-field"]
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }
    
    public static function delete() {
        Partner::delete($_POST['id-field']);
    }

    public static function update() {
        try {
            PartnerController::validate();
            $partner = Partner::fetch($_POST["id-field"]);
            $partner
                ->setName($_POST["name-field"])
                ->setWebpage($_POST["link-field"])
                ->updateDatabase();
            if(strlen($_FILES["logo-field"]["name"])>0) {
                ImageValidator::checkImageType($_FILES["logo-field"]['type']);
                $partner
                    ->setLogo(file_get_contents($_FILES["logo-field"]["tmp_name"]))
                    ->updateDatabase();
            }
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }

    public static function handleRequest(): void {
        switch($_POST["action"]) {
            case 'create':
                PartnerController::new();
                break;
            case 'delete':
                PartnerController::delete();
                break;
            case 'update':
                PartnerController::update();
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

PartnerController::handleRequest();
PartnerController::redirect();