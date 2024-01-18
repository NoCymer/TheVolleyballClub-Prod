<?php

namespace Controllers;

use Controllers\AbstractController;
use Exceptions\DisplayableException;
use Models\Group;

class GroupController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        return true;
    }

    public static function new() {
        try {
            GroupController::validate();
            Group::new(
                $_POST["name-field"],
                $_POST["description-field"]
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }
    
    public static function delete() {
        Group::delete($_POST['id-field']);
    }

    public static function update() {
        try {
            GroupController::validate();

            Group::fetch($_POST["id-field"])
                ->setName($_POST["name-field"])
                ->setDescription($_POST["description-field"])
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
                GroupController::new();
                break;
            case 'delete':
                GroupController::delete();
                break;
            case 'update':
                GroupController::update();
                break;
        }
    }
    public static function redirect() {
    }
}

GroupController::handleRequest();
GroupController::redirect();

/**
 * Fields -> [
 *  "name" : string
 *  "desc" : string
 * ]
 */