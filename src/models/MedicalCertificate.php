<?php

namespace Models;
use Database\DatabaseConnection;

class MedicalCertificate extends AbstractModel{
    private int $id;
    private string $blob;
    private bool | null $is_valid;
    private string $type;
    private string $file_name;

    public function getType() {
        return $this->type;
    }
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function getFileName() {
        return $this->file_name;
    }
    public function setFileName($file_name) {
        $this->file_name = $file_name;
        return $this;
    }

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM medicalCertificate WHERE medicalCertificateId=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $medical_certificate = $stmt->fetch();

        $this->blob = $medical_certificate["medicalCertificate"];
        $this->type = $medical_certificate["type"];
        $this->file_name = $medical_certificate["fileName"];
        $this->is_valid = $medical_certificate["valid"];
    }

    public static function fetch($id): MedicalCertificate{
        return new MedicalCertificate($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getDocument() {
        return $this->blob;
    }
    public function setDocument(string $blob): MedicalCertificate {
        $this->blob = $blob;
        return $this;
    }

    public function getIsValid(): bool | null  {
        return $this->is_valid;
    }
    public function setIsValid(bool $is_valid): MedicalCertificate {
        $this->is_valid = $is_valid;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE medicalCertificate
            SET medicalCertificate=?,
            `type`=?,
            `fileName`=?,
            `valid`=b?
            WHERE medicalCertificateId=?'
        );
        $stmt->bindValue(1, $this->blob);
        $stmt->bindValue(2, $this->type);
        $stmt->bindValue(3, $this->file_name);
        $stmt->bindValue(4, $this->is_valid);
        $stmt->bindValue(5, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new medicalcertificate in the database
     * @return int id of the new entry
     */
    public static function new(
        string $blob,
        string $type,
        string $file_name
    ) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO medicalCertificate (
                medicalCertificate,
                `type`,
                `fileName`,
                valid
            ) 
            VALUES (?,?,?,NULL)'
        );
        $stmt->bindValue(1, $blob);
        $stmt->bindValue(2, $type);
        $stmt->bindValue(3, $file_name);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the medicalcertificate having the given id
     * @param int $id id of the medicalcertificate to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM medicalCertificate WHERE medicalCertificateId=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}