<?php

namespace Models;
use Database\DatabaseConnection;

class Group extends AbstractModel{
    private int $id;
    private string $name;
    private string $description;

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM `group` WHERE idGroup=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $group = $stmt->fetch();

        $this->name = $group["nameGroup"];
        $this->description = $group["descGroup"];
    }

    public static function fetch($id): Group{
        return new Group($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName(string $name): Group {
        $this->name = $name;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }
    public function setDescription(string $description): Group {
        $this->description = $description;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE `group`
            SET nameGroup=?,
            descGroup=?,
            WHERE idPartner=?'
        );
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, $this->description);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new group in the database
     * @return int id of the new entry
     */
    public static function new(
        string $name,
        string $description
    ) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO `group` (
                nameGroup,
                descGroup
            ) 
            VALUES (?,?)'
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $description);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the group having the given id
     * @param int $id id of the group to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM `group` WHERE idGroup=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}