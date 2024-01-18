<?php

namespace Controllers;

use Controllers\AbstractController;
use Exceptions\DisplayableException;
use Models\Event;
use Validation\DateTimeValidator;
use Validation\ImageValidator;

class EventController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        DateTimeValidator::checkDateTimeValidity($_POST["start-date-time-field"]);
        DateTimeValidator::checkDateTimeValidity($_POST["end-date-time-field"]);
    }

    public static function new() {
        try {
            EventController::validate();

            Event::new(
                $_POST["name-field"],
                $_POST["description-field"],
                $_POST["start-date-time-field"],
                $_POST["end-date-time-field"],
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }
    
    public static function delete() {
        Event::delete($_POST['id-field']);
    }

    public static function update() {
        try {
            EventController::validate();

            Event::fetch($_POST["id-field"])
                ->setName($_POST["name-field"])
                ->setDescription($_POST["description-field"])
                ->setStartDateTime($_POST["start-date-time-field"])
                ->setEndDateTime($_POST["end-date-time-field"])
                ->updateDatabase();
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;
        }
    }

    public static function addImage(): void {
        ImageValidator::checkImageType($_FILES["image-field"]['type']);

        Event::fetch($_POST["id-field"])
            ->addImage(file_get_contents($_FILES["image-field"]["tmp_name"]));
    }

    public static function removeImage(): void {
        Event::fetch($_POST["id-field"])
            ->removeImage($_POST["image-removed-id"]);
    }

    public static function handleRequest(): void {
        switch($_POST["action"]) {
            case 'create':
                EventController::new();
                break;
            case 'delete':
                EventController::delete();
                break;
            case 'update':
                EventController::update();
                break;
            case 'add-image':
                EventController::addImage();
                break;
            case 'remove-image':
                EventController::removeImage();
                break;
        }
    }
    public static function redirect() {
        header('Location: /dashboard/events');
    }
}

EventController::handleRequest();
EventController::redirect();