<?php

namespace Controllers;

use Authorization\Authenticator;
use Authorization\AuthorizationLevel;
use Models\User;
use Exceptions\DisplayableException;
use Cryptography\PasswordManager;
use Controllers\AbstractController;
use Exceptions\InvalidPasswordException;
use Models\Competition;
use Models\IdCard;
use Models\MedicalCertificate;
use Validation\AccountValidator;

class UserController extends AbstractController implements IRequestHandler{
    protected static function validate() {
        AccountValidator::checkEmailFormat($_POST['email-field']);
        AccountValidator::checkValidName($_POST['name-field']);
        AccountValidator::checkValidSurname($_POST['surname-field']);
        AccountValidator::checkValidGender($_POST['gender-field']);
        AccountValidator::checkValidGroup($_POST['group-field']);
        AccountValidator::checkValidBirthdate($_POST['birthdate-field']);
    }

    public static function new() {
        try {
            AccountValidator::checkPasswordStrength($_POST['password-field']);
            AccountValidator::checkEmailUnicity($_POST['email-field']);
            AccountValidator::checkAvatarType($_FILES["avatar-field"]['type']);
            AccountValidator::checkAvatarSize($_FILES["avatar-field"]['size']);
            UserController::validate();
    
            // Temporary payment id
            // TODO: Replace it with real payment id
            $PAYMENT_ID_TEMP = 1;
        
            // Creates a new user in the database
            $id = User::new(
                $_POST['name-field'],
                $_POST['surname-field'],
                PasswordManager::hash($_POST['password-field']),
                $_POST['email-field'],
                $_POST['birthdate-field'],
                $_POST['gender-field'],
                file_get_contents($_FILES["avatar-field"]['tmp_name']),
                $_POST['group-field'],
                $PAYMENT_ID_TEMP
            );
    
            // Connects the newly created user
            $_SESSION['userConnect'] = $id; 
    
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
        User::delete($_POST['id-field']);
    }

    public static function handleMedicalCertificateUpload() {
        try {
            AccountValidator::checkDocumentType($_FILES["medical-certificate-field"]['type']);
            AccountValidator::checkDocumentSize($_FILES["medical-certificate-field"]['size']);
            $user = User::fetch($_POST['id-field']);
            if(!is_null($user->getMedicalCertificateId())) {
                $medical_certificate = MedicalCertificate::fetch($user->getMedicalCertificateId());
                if($medical_certificate->getIsValid()) {
                    return;
                } else {
                    $user->removeMedicalCertificate();
                }
            }

            $user->addMedicalCertificate(
                file_get_contents($_FILES["medical-certificate-field"]['tmp_name']),
                $_FILES["medical-certificate-field"]['type'],
                $_FILES["medical-certificate-field"]['name']
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();

            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;            
        }
    }
    public static function handleIdCardUpload() {
        try {
            var_dump($_FILES["id-card-field"]);
            AccountValidator::checkDocumentType($_FILES["id-card-field"]['type']);
            AccountValidator::checkDocumentSize($_FILES["id-card-field"]['size']);
            $user = User::fetch($_POST['id-field']);
            if(!is_null($user->getIdCardId())) {
                $id_card = IdCard::fetch($user->getIdCardId());
                if($id_card->getIsValid()) {
                    return;
                } else {
                    $user->removeIdCard();
                }
            }

            $user->addIdCard(
                file_get_contents($_FILES["id-card-field"]['tmp_name']),
                $_FILES["id-card-field"]['type'],
               $_FILES["id-card-field"]['name']
            );
        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;            
        }
    }

    public static function handleMedicalCertificateDeletion() {
        User::fetch($_POST['id-field'])->removeMedicalCertificate();
    }
    public static function handleIdCardDeletion() {
        User::fetch($_POST['id-field'])->removeIdCard();
    }

    public static function handleMedicalCertificateValidation() {
        User::fetch($_GET['user'])->setValidationStateMedicalCertificate(
            $_GET['action'] == "validate" ? true : ($_GET['action'] == "invalidate" ? false : null)
        );
    }
    public static function handleIdCardValidation() {
        User::fetch($_GET['user'])->setValidationStateIdCard(
            $_GET['action'] == "validate" ? true : ($_GET['action'] == "invalidate" ? false : null)
        );
    }

    public static function handleParticipationValidation() {
        Competition::fetch($_GET['competition'])->setParticipationValidation(
            $_GET['user'],
            $_GET['action'] == "validateParticipation" ? true : ($_GET['action'] == "invalidateParticipation" ? false : null)
        );
    }

    public static function update() {
        try {
            $user = User::fetch($_POST['id-field']);
            AccountValidator::checkEmailUnicity($_POST['email-field'], $user->getEmail());

            $has_new_avatar = (strlen($_FILES["avatar-field"]["name"]) > 0);

            if($has_new_avatar) {
                AccountValidator::checkAvatarType($_FILES["avatar-field"]['type']);
                AccountValidator::checkAvatarSize($_FILES["avatar-field"]['size']);
            }

            UserController::validate();

            $user 
                ->setEmail($_POST['email-field'])
                ->setName($_POST['name-field'])
                ->setSurname($_POST['surname-field'])
                ->setGender($_POST['gender-field'])
                ->setGroupID($_POST['group-field'])
                ->setBirthdate($_POST['birthdate-field']);

            if($has_new_avatar)
                $user->setImageUser(file_get_contents($_FILES["avatar-field"]['tmp_name']));

            $user->updateDatabase();

        } catch(DisplayableException $e) {
            $_SESSION["error"] = $e->getErrorCode();
    
            // Sends back the form's data to refill the form
            $_SESSION['form-data'] = $_POST;            
        }
    }

    public static function changePassword() {
        try {
            $user = User::fetch($_POST['id-field']);
            
            switch(Authenticator::authenticate($user->getEmail(), PasswordManager::hash($_POST['old-password-field']))) {
                case AuthorizationLevel::User:
                    break;
                default:
                    throw new InvalidPasswordException();
            }

            AccountValidator::checkPasswordStrength($_POST['password-field']);
            
            $user
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
                UserController::new();
                break;
            case 'delete':
                // Delete only if the user requesting the deletion is the one connected
                // Or if the admin is connected
                if(
                    ($_SESSION['userConnect']==$_POST['id-field']) ||
                    (isset($_SESSION['adminConnect']))
                ) UserController::delete();
                break;
            case 'update':
                // Edit only if the user requesting the edition is the one connected
                // Or if the admin is connected
                if(
                    ($_SESSION['userConnect']==$_POST['id-field']) ||
                    (isset($_SESSION['adminConnect']))
                ) UserController::update();        
                break;
            case 'upload-medical-certificate':
                // Upload only if the user requesting the edition is the one connected
                if(
                    ($_SESSION['userConnect']==$_POST['id-field'])
                ) UserController::handleMedicalCertificateUpload();     
                break;
            case 'upload-id-card':
                // Upload only if the user requesting the edition is the one connected
                if(
                    ($_SESSION['userConnect']==$_POST['id-field'])
                ) UserController::handleIdCardUpload();     
                break;
            case 'change-password':
                // Edit only if the admin requesting the edition is the one connected
                if(!($_SESSION['userConnect'] == $_POST['id-field']))  {
                    header("Location: /"); 
                } UserController::changePassword();
                break;
        }
        switch($_GET["action"]){
            case 'invalidate':
            case 'validate':
                switch($_GET["document"]){
                    case 'medicalCertificate':
                        // Validate only if the user requesting the edition is the admin
                        if(
                            isset($_SESSION['adminConnect'])
                        ) UserController::handleMedicalCertificateValidation();    
                        break;
                    case 'idCard':
                        // Validate only if the user requesting the edition is the admin
                        if(
                            isset($_SESSION['adminConnect'])
                        ) UserController::handleIdCardValidation();    
                        break;
                }   
                break;
            case 'validateParticipation':
            case 'invalidateParticipation':
                // Validate only if the user requesting the edition is the admin
                if(
                    isset($_SESSION['adminConnect'])
                ) UserController::handleParticipationValidation();    
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
UserController::handleRequest();
UserController::redirect();