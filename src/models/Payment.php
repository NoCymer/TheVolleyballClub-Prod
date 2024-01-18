<?php

namespace Models;
use Database\DatabaseConnection;

class Payment extends AbstractModel{
    private int $id;
    private string $mean;
    private int $amount;

    private function __construct($id){
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM payment WHERE idPayment=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $payment = $stmt->fetch();

        $this->mean = $payment["meanPayment"];
        $this->amount = $payment["amountPayment"];
    }

    public static function fetch($id): Payment{
        return new Payment($id);
    }

    public function getId() {
        return $this->id;
    }

    public function getMean() {
        return $this->mean;
    }
    public function setMean(string $mean): Payment {
        $this->mean = $mean;
        return $this;
    }

    public function getAmount() {
        return $this->amount;
    }
    public function setAmount(int $amount): Payment {
        $this->amount = $amount;
        return $this;
    }

    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE payment
            SET meanPayment=?,
            amountPayment=?
            WHERE idPayment=?'
        );
        $stmt->bindValue(1, $this->mean);
        $stmt->bindValue(2, $this->amount);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();
    }

    /**
     * Creates a new payment in the database
     * @return int id of the new entry
     */
    public static function new(
        string $mean,
        string $amount
    ) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO payment (
                meanPayment,
                amountPayment
            ) 
            VALUES (?,?)'
        );
        $stmt->bindValue(1, $mean);
        $stmt->bindValue(2, $amount);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();
    }

    /**
     * Deletes from the database the payment having the given id
     * @param int $id id of the payment to delete
     */
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM payment WHERE idPayment=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
}