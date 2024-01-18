<?php

namespace Models;

use Optimizations\ImageCompressor;
use Database\DatabaseConnection;

class User extends AbstractModel{
    private int $id;
    private string $name;
    private string $surname;
    private string $password;
    private string $email;
    private string $birthdate;
    private string $register_date;
    private int $gender;
    private int $group_id;
    private int $payment_id;
    private string $image_user;
    private int | null $id_card_id;
    private int | null $medical_certificate_id;
    private array $competitions = array();
    private array $trainings = array();

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM user WHERE idUser=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $user = $stmt->fetch();

        $this->name = $user["nameUser"];
        $this->surname = $user["surnameUser"];
        $this->password = $user["passwordUser"];
        $this->email = $user["emailUser"];
        $this->birthdate = $user["birthdateUser"];
        $this->register_date = $user["registerDate"];
        $this->gender = $user["gender"];
        $this->group_id = $user["Group_idGroup"];
        $this->payment_id = $user["payment_idPayment"];
        $this->image_user = $user["imageUser"];
        $this->medical_certificate_id = $user["medicalCertificate_id"];
        $this->id_card_id = $user["idCard_id"];

        
        // Fetches all the competitions the user is subscribed to
        $stmt = $this->getConnection()->prepare('SELECT * FROM user_has_competition WHERE User_idUser=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $competitions = $stmt->fetchAll();
        foreach($competitions as $competition) array_push($this->competitions, $competition["Competition_idCompetition"]);

        // Fetches all the training the user is subscribed to
        $stmt = $this->getConnection()->prepare('SELECT * FROM user_has_training WHERE User_idUser=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $trainings = $stmt->fetchAll();
        foreach($trainings as $training) array_push($this->trainings, $training["Training_idTraining"]);
    }

    public static function fetch($id): User{
        return new User($id);
    }

    public function getIdCardId() {
        return $this->id_card_id;
    }

    public function addIdCard($blob, $type, $file_name) {
        $this->id_card_id = IdCard::new($blob, $type, $file_name);

        $stmt = $this->getConnection()->prepare(
            'UPDATE user
            SET idCard_id=?
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->id_card_id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();
    }
    public function removeIdCard() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE user
            SET idCard_id=NULL
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->id);
        $stmt->execute();

        IdCard::delete($this->id_card_id);
        $this->id_card_id = null;
    }

    public function getMedicalCertificateId() {
        return $this->medical_certificate_id;
    }

    public function addMedicalCertificate($blob, $type, $file_name) {
        $this->medical_certificate_id = MedicalCertificate::new($blob, $type, $file_name);

        $stmt = $this->getConnection()->prepare(
            'UPDATE user
            SET medicalCertificate_id=?
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->medical_certificate_id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();
        
    }
    public function removeMedicalCertificate() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE user
            SET medicalCertificate_id=NULL
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->id);
        $stmt->execute();

        MedicalCertificate::delete($this->medical_certificate_id);
        $this->medical_certificate_id = null;
    }


    /**
     * Changes the validation state of the user's medical
     * certificatie
     * @param integer $user_id
     * @param bool $validation_state true for valid false for invalid 
     * @return void
     */
    public function setValidationStateMedicalCertificate(bool $validation_state) {
        $medical_certificate = MedicalCertificate::fetch(
            $this->medical_certificate_id
        );
        $medical_certificate->setIsValid($validation_state);
        $medical_certificate->updateDatabase();
    }

    /**
     * Changes the validation state of the user's id card
     * @param integer $user_id
     * @param bool $validation_state true for valid false for invalid 
     * @return void
     */
    public function setValidationStateIdCard(bool $validation_state) {
        $id_card = IdCard::fetch(
            $this->id_card_id
        );
        $id_card->setIsValid($validation_state);
        $id_card->updateDatabase();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName(string $name): User {
        $this->name = $name;
        return $this;
    }


    public function getSurname() {
        return $this->surname;
    }
    public function setSurname(string $surname): User {
        $this->surname = $surname;
        return $this;
    }


    public function getPassword() {
        return $this->password;
    }

    public function changePassword(string $password) {
        $this->password = $password;
        $stmt = $this->getConnection()->prepare(
            'UPDATE `user`
            SET passwordUser=?
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->password);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();
    }


    public function getEmail() {
        return $this->email;
    }
    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }


    public function getGender() {
        return $this->gender;
    }
    public function setGender(int $gender): User {
        $this->gender = $gender;
        return $this;
    }


    public function getRegisterDate() {
        return $this->register_date;
    }


    public function getBirthdate() {
        return $this->birthdate;
    }
    public function setBirthdate(string $birthdate): User {
        $this->birthdate = $birthdate;
        return $this;
    }


    public function getImageUser() {
        return $this->image_user;
    }
    public function setImageUser(string $image_user): User {
        $this->image_user = ImageCompressor::compress($image_user);
        return $this;
    }


    public function getGroupID() {
        return $this->group_id;
    }
    public function setGroupID(int $group_id): User {
        $this->group_id = $group_id;
        return $this;
    }


    public function getPaymentID() {
        return $this->payment_id;
    }
    public function setPaymentID(int $payment_id): User {
        $this->payment_id = $payment_id;
        return $this;
    }

    /**
     * Returns the list of competitions the user is currently subscribed to
     * @return array List of competition the user is subscribed to
     */
    public function getCompetitions(): array {
        return $this->competitions;
    }

    /**
     * Returns the list of trainings the user is currently subscribed to
     * @return array List of trainings the user is subscribed to
     */
    public function getTrainings(): array {
        return $this->trainings;
    }

    /**
     * Checks if the user is participating to the given competition
     * @param $competitionId Id of the competition to check
     * @return bool True if the user participates to the competition
     */
    public function isParticipatingToCompetition(int $competitionId): bool {
        return in_array($competitionId, $this->competitions);
    }

    /**
     * Checks if the user is participating to the given training
     * @param $trainingId Id of the training to check
     * @return bool True if the user participates to the training
     */
    public function isParticipatingToTraining(int $trainingId): bool {
        return in_array($trainingId, $this->trainings);
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE user
            SET nameUser=?,
            surnameUser=?,
            emailUser=?,
            birthdateUser=?,
            gender=?,
            imageUser=?,
            Group_idGroup=?,
            payment_idPayment=?,
            medicalCertificate_id=?,
            idCard_id=?
            WHERE idUser=?'
        );
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, $this->surname);
        $stmt->bindValue(3, $this->email);
        $stmt->bindValue(4, $this->birthdate);
        $stmt->bindValue(5, $this->gender);
        $stmt->bindValue(6, $this->image_user);
        $stmt->bindValue(7, $this->group_id);
        $stmt->bindValue(8, $this->payment_id);
        $stmt->bindValue(9, $this->medical_certificate_id);
        $stmt->bindValue(10, $this->id_card_id);
        $stmt->bindValue(11, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new user in the database
     * @return int id of the new entry
     */
    public static function new(
        string $name,
        string $surname,
        string $password,
        string $email,
        string $birthdate,
        int $gender,
        string $image_user,
        int $group_id,
        int $payment_id
    ) {
        date_default_timezone_set('Europe/Paris');

        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO user (
                nameUser,
                surnameUser,
                passwordUser,
                emailUser,
                birthdateUser,
                gender,
                imageUser,
                Group_idGroup,
                payment_idPayment,
                registerDate
            ) 
            VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $surname);
        $stmt->bindValue(3, $password);
        $stmt->bindValue(4, $email);
        $stmt->bindValue(5, $birthdate);
        $stmt->bindValue(6, $gender);
        $stmt->bindValue(7, ImageCompressor::compress($image_user));
        $stmt->bindValue(8, $group_id);
        $stmt->bindValue(9, $payment_id);
        $stmt->bindValue(10, date('Y-m-d'));

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the user having the given id
     * @param int $id id of the user to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM user WHERE idUser=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}