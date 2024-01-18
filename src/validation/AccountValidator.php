<?php

namespace Validation;

use Exceptions\EmailAlreadyExistsException;
use Exceptions\EmailFormatException;
use Exceptions\InvalidImageTypeException;
use Exceptions\InvalidDocumentTypeException;
use Exceptions\InvalidDocumentSizeException;
use Exceptions\InvalidAvatarSizeException;
use Exceptions\InvalidBirthdateException;
use Exceptions\InvalidGenderException;
use Exceptions\InvalidGroupException;
use Exceptions\InvalidNameException;
use Exceptions\InvalidSurnameException;
use Exceptions\WeakPasswordException;

use Database\DatabaseConnection;

class AccountValidator {
    private static $valid_img_types = ["image/png", "image/jpeg", "image/webp", "image/jpg"];
    private static $specialSymbols = " !\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~";
    private static $invalidNameSymbols = "!\"#$%&'()*+,./:;<=>?@[\\]^`{|}~";
    private static $uppercaseSymbols = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    private static $numbers = "1234567890";
    private static $email_regex = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";

    /**
     * Verifies that the email does not already exist in admin or user table
     * @param string $email Email to check
     * @param string $exclude email string to ignore
     * @throws EmailAlreadyExistsException If email not unique
     */
    public static function checkEmailUnicity($email, $exclude = "") {
        /** Connection to the database */
        $connection = new DatabaseConnection();
        //Verify if the email already exists as admin or user
        $stmt = $connection->getConnection()->prepare(
            'SELECT idUser FROM user WHERE emailUser=? AND NOT emailUser=?
            UNION
            SELECT idAdmin FROM admin WHERE loginAdmin=? AND NOT loginAdmin=?'
        );
        $stmt->execute([$email, $exclude, $email, $exclude]);
        $user = $stmt->fetch();

        if(
            isset($user['idUser']) || isset($admin['idAdmin'])
        ) throw new EmailAlreadyExistsException();
    }

    /**
     * Verifies that the email is valid (has an @ and a domain)
     * @param string $email Email to check
     * @throws EmailFormatException If email is invalid
     */
    public static function checkEmailFormat($email) {
        switch(preg_match(AccountValidator::$email_regex,$email)) {
            case 0:
            case false:
                throw new EmailFormatException();
        }
    }

    /**
     * Checks if the provided string contains any of the character provided in the list
     * @param string $string String to check
     * @param string $list charlist in form of a string
     */
    public static function has($string,$list){
        foreach(str_split($list) as $symbol) if(str_contains($string,$symbol))return true;
        return false;
    }

    /**
     * Verifies that the password is secured enough
     * @param string $password Password to check
     * @throws WeakPasswordException If the password is weak
     */
    public static function checkPasswordStrength($password) {
        if(strlen($password) < 12) throw new WeakPasswordException();
        if(!AccountValidator::has($password,AccountValidator::$specialSymbols)) throw new WeakPasswordException();
        if(!AccountValidator::has($password,AccountValidator::$uppercaseSymbols)) throw new WeakPasswordException();
        if(!AccountValidator::has($password,AccountValidator::$numbers)) throw new WeakPasswordException();
    }
    
    /**
     * Verifies that the avatar size does not exeeds 2MB
     * @param int $avatar_size Size of the avatar
     * @throws InvalidAvatarSizeException
     */
    public static function checkAvatarSize(int $avatar_size) {
        if($avatar_size > 2097152) throw new InvalidAvatarSizeException();
    }
    
    /**
     * Verifies that the avatar is an image and is valid
     * @param string $avatar_type type of the image
     * @throws InvalidImageTypeException If avatar is invalid
     */
    public static function checkAvatarType(string $avatar_type) {
        ImageValidator::checkImageType($avatar_type);
    }
    
    /**
     * Verifies that the gender is valid (1 or 0)
     * @param int $gender Gender to check
     * @throws InvalidGenderException if gender is invalid
     */
    public static function checkValidGender(int $gender) {
        if($gender !=1 && $gender != 0) throw new InvalidGenderException();
    }

    /**
     * Verifies that the birthdate is in the correct format and the user is more than 15 years old
     * @param string $birthdate Birthdate to check
     * @throws InvalidBirthdateException If birthdate is invalid
     */
    public static function checkValidBirthdate(string $birthdate) {
        $SECS_YEAR = 31536000;
        $MIN_AGE = 15;
        $minDate = time() - ($SECS_YEAR*$MIN_AGE);
        $bd = strtotime($birthdate);
        
        //If parsing failed
        if(!$bd) throw new InvalidBirthdateException();

        // If user is too young
        if($bd > $minDate) throw new InvalidBirthdateException();
    }
    
    /**
     * Verifies that the name is valid (no number)
     * @param string $name Name to check
     * @throws InvalidNameException If name invalid
     */
    public static function checkValidName(string $name) {
        if(AccountValidator::has($name,AccountValidator::$invalidNameSymbols)) throw new InvalidNameException();
        if(AccountValidator::has($name,AccountValidator::$numbers)) throw new InvalidNameException();
    }
    
    /**
     * Verifies that the surname is valid (no number)
     * @param string $surname Surname to check
     * @throws InvalidSurnameException If surname invalid
     */
    public static function checkValidSurname(string $surname) {
        if(AccountValidator::has($surname,AccountValidator::$invalidNameSymbols)) throw new InvalidSurnameException();
        if(AccountValidator::has($surname,AccountValidator::$numbers)) throw new InvalidSurnameException();
    }

    /**
     * Verifies that the group is valid
     * @param int $groupid Group to check
     * @throws InvalidGroupException If group invalid
     */
    public static function checkValidGroup(int $groupid) {
        if($groupid !=1 && $groupid != 2) throw new InvalidGroupException();
    }

    /**
     * Verifies that the document is valid
     * @param string $document_type type of the document
     * @throws InvalidDocumentTypeException If document is invalid
     */
    public static function checkDocumentType(string $document_type){
        DocumentValidator::checkDocumentType($document_type);
    }

    /**
     * Verifies that the document size does not exeeds 3MB
     * @param int $document_size Size of the avatar
     * @throws InvalidDocumentSizeException
     */
    public static function checkDocumentSize(int $document_size){
        if($document_size > 3145728) throw new InvalidDocumentSizeException();
    }

}