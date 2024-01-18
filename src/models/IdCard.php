<?php

namespace Models;
use Database\DatabaseConnection;

class IdCard extends AbstractModel{
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
        $stmt = $this->getConnection()->prepare('SELECT * FROM idCard WHERE idCardId=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $id_card = $stmt->fetch();

        $this->blob = $id_card["idCard"];
        $this->type = $id_card["type"];
        $this->file_name = $id_card["fileName"];
        $this->is_valid = $id_card["valid"];
    }

    public static function fetch($id): IdCard{
        return new IdCard($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getDocument() {
        return $this->blob;
    }
    public function setDocument(string $blob): IdCard {
        $this->blob = $blob;
        return $this;
    }

    public function getIsValid() {
        return $this->is_valid;
    }
    public function setIsValid(bool $is_valid): IdCard {
        $this->is_valid = $is_valid;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE idCard
            SET `idCard`=?,
            `type`=?,
            `fileName`=?,
            `valid`=b?
            WHERE idCardId=?'
        );
        $stmt->bindValue(1, $this->blob);
        $stmt->bindValue(2, $this->type);
        $stmt->bindValue(3, $this->file_name);
        $stmt->bindValue(4, boolval($this->is_valid));
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
            'INSERT INTO idCard (
                idCard,
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
            'DELETE FROM idCard WHERE idCardId=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}