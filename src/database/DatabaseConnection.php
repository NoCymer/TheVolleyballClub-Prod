<?php

namespace Database;

class DatabaseConnection {
    private static $serverName = "localhost";
    private static $username = "root";
    private static $password = "Pabloescobar";
    private static $database = "volleyball_club";
    private $connection;
    public function __construct(){
        try{
            $this->connection = new \PDO(
                "mysql:host=".DatabaseConnection::$serverName.";dbname=".DatabaseConnection::$database,
                DatabaseConnection::$username,DatabaseConnection::$password
            );
            $this->connection->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            );
        }catch(\PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}