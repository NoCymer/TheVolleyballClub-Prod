<?php

namespace Models;
use Database\DatabaseConnection;

class Partner extends AbstractModel{
    private int $id;
    private string $name;
    private string $logo;
    private string $webpage;

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM partner WHERE idPartner=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $admin = $stmt->fetch();

        $this->name = $admin["namePartner"];
        $this->logo = $admin["logoPartner"];
        $this->webpage = $admin["webpagePartner"];
    }

    public static function fetch($id): Partner{
        return new Partner($id);
    }
    /**
     * @return Partner[] List of all partners
     */
    public static function fetchAll(): array{
        $partners = array();
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare("SELECT idPartner FROM partner");
        $stmt->execute();
        foreach($stmt->fetchAll() as $res)
            array_push($partners, new Partner($res["idPartner"]));
        return $partners;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName(string $name): Partner {
        $this->name = $name;
        return $this;
    }

    public function getLogo() {
        return $this->logo;
    }
    public function setLogo(string $logo): Partner {
        $this->logo = $logo;
        return $this;
    }

    public function getWebpage() {
        return $this->webpage;
    }
    public function setWebpage(string $webpage): Partner {
        $this->webpage = $webpage;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE partner
            SET namePartner=?,
            logoPartner=?,
            webpagePartner=?
            WHERE idPartner=?'
        );
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, $this->logo);
        $stmt->bindValue(3, $this->webpage);
        $stmt->bindValue(4, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new partner in the database
     * @return int id of the new entry
     */
    public static function new(
        string $name,
        string $logo,
        string $website
    ) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO partner (
                namePartner,
                logoPartner,
                webpagePartner 	
            ) 
            VALUES (?,?,?)'
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $logo);
        $stmt->bindValue(3, $website);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the partner having the given id
     * @param int $id id of the partner to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM partner WHERE idPartner=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}