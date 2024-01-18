<?php

namespace Models;
use Database\DatabaseConnection;

class Result extends AbstractModel{
    private int $id;
    private int $victories_count;
    private int $defeat_count;
    private int $ranking;
    private int $total_clubs_count;

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM result WHERE idResult=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $result = $stmt->fetch();

        $this->victories_count = $result["victoriesCount"];
        $this->defeat_count = $result["defeatCount"];
        $this->ranking = $result["ranking"];
        $this->total_clubs_count = $result["totalClubsCount"];
    }

    public static function fetch($id): Result{
        return new Result($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getVictoriesCount() {
        return $this->victories_count;
    }
    public function setVictoriesCount(int $victoriescount): Result {
        $this->victories_count = $victoriescount;
        return $this;
    }

    public function getDefeatCount() {
        return $this->defeat_count;
    }
    public function setDefeatCount(int $defeatcount): Result {
        $this->defeat_count = $defeatcount;
        return $this;
    }

    public function getRanking() {
        return $this->ranking;
    }
    public function setRanking(int $ranking): Result {
        $this->ranking = $ranking;
        return $this;
    }

    public function getTotal_clubs_count() {
        return $this->total_clubs_count;
    }
    public function setTotal_clubs_count(int $total_clubs_count): Result {
        $this->total_clubs_count = $total_clubs_count;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE result
            SET victoriesCount=?,
            defeatCount=?,
            ranking=?,
            totalClubsCount=?
            WHERE idResult=?'
        );
        $stmt->bindValue(1, $this->victories_count);
        $stmt->bindValue(2, $this->defeat_count);
        $stmt->bindValue(3, $this->ranking);
        $stmt->bindValue(4, $this->total_clubs_count);
        $stmt->bindValue(5, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new result in the database
     * @return int id of the new entry
     */
    public static function new(
        int $victories_count,
        int $defeat_count,
        int $ranking,
        int $total_clubs_count
    ) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO result (
                victoriesCount,
                defeatCount,
                ranking,
                totalClubsCount
            ) 
            VALUES (?,?,?,?)'
        );
        $stmt->bindValue(1, $victories_count);
        $stmt->bindValue(2, $defeat_count);
        $stmt->bindValue(3, $ranking);
        $stmt->bindValue(4, $total_clubs_count);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the result having the given id
     * @param int $id id of the result to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM result WHERE idResult=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}