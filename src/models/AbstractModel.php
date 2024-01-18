<?php

namespace Models;

use Database\DatabaseConnection;

abstract class AbstractModel {
    private $connection;

    protected function __construct(){
        $this->connection = new DatabaseConnection();
    }

    /**
     * Fetches the entry in the database corresponding to the $id
     * @param int $id Id to fetch
     * @return AbstractModel Entry fetched
     */
    public abstract static function fetch(int $id): AbstractModel;

    /**
     * Deletes from the databese the entry having $id as its id
     * @param int $id Id to delete
     */
    public abstract static function delete(int $id);

    /**
     * Updates the database to match the stored values if they have
     * been modified
     */
    public abstract function updateDatabase();

    /**
     * Gets the connection to the database
     * @return \PDO Connection to the database
     */

    protected function getConnection() {
        return $this->connection->getConnection();
    }
}