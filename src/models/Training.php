<?php

namespace Models;
use Database\DatabaseConnection;

class Training extends AbstractModel{
    private int $id;
    private string $start_date_time;
    private string $end_date_time;
    private int $location_id;
    private int $max_participant_training; 	
    private array $participants = array();

    private static int $MAX_TIME_BEFORE_INSCRIPTION = 86400; // in seconds

    private function __construct($id) {
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM training WHERE idTraining=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $training = $stmt->fetch();

        $this->start_date_time = $training["startDateTimeTraining"];
        $this->end_date_time = $training["endDateTimeTraining"];
        $this->location_id = $training["Location_idLocation"];
        $this->max_participant_training = $training["maxParticipantTraining"];

        // Fetches all the users participating to the training
        $stmt = $this->getConnection()->prepare('SELECT * FROM user_has_training WHERE Training_idTraining=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $participants = $stmt->fetchAll();
        foreach($participants as $participant) array_push($this->participants, $participant["User_idUser"]);
    }

    /**
     * Adds the given participant to the event if it is possible
     * NOTE : does not require to call updateDatabase() 
     * As it instantly modifies the database
     * @param int $id Id of the participant to add
     * @return bool True if the addition was successful, else false
     */
    public function addParticipant(int $id): bool {
        if($this->getPlacesLeft() <= 0 || $this->hasExpired()) return false;

        $stmt = $this->getConnection()->prepare(
            'INSERT INTO user_has_training 
            (User_idUser, Training_idTraining)
            VALUES (?,?)'
        );
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();

        // Adds the participant to the participants array
        array_push($this->participants, $id);
        return true;
    }

    /**
     * Removes the given participant to the event if it is possible
     * NOTE : does not require to call updateDatabase() 
     * As it instantly modifies the database
     * @param int $id Id of the participant to remove
     * @return bool True if the removal was successful, else false
     */
    public function removeParticipant(int $id): bool {
        if(!in_array($id, $this->participants) || $this->hasExpired()) return false;

        $stmt = $this->getConnection()->prepare(
            'DELETE FROM user_has_training
            WHERE User_idUser=? AND Training_idTraining=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();

        // Removes the participant from the participants array
        $this->participants = array_diff($this->participants, array($id));
        return true;
    }

    /**
     * Returns the number of places left in the training
     * @return int Places left in the training
     */
    public function getPlacesLeft(): int {
        return $this->max_participant_training - count($this->participants);
    }

    /**
     * Checks if the training has expired (start date passed)
     * @return bool True if has expired, else false
     */
    public function hasExpired(): bool {
        return time() > (strtotime($this->start_date_time) - Training::$MAX_TIME_BEFORE_INSCRIPTION);
    }

    /**
     * Checks if the training has passsed (start date)
     * @return bool True if has passsed, else false
     */
    public function hasPassed(): bool {
        return time() > strtotime($this->start_date_time);
    }

    public function getId() {
        return $this->id;
    }

    public function getStartDateTime(): int {
        return strtotime($this->start_date_time);
    }

    public function getEndDateTime(): int {
        return strtotime($this->end_date_time);
    }

    public function getLocationId():int {
        return $this->location_id;
    }

    public function getParticipantsCount(): int {
        return count($this->participants);
    }

    public static function fetch($id): Training{
        return new Training($id);
    }

    /**
     * Returns all existing trainings
     * @return Training[] existing trainings
     */
    public static function fetchAll(): array {
        $trainings = array();
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare("SELECT idTraining FROM training ORDER BY startDateTimeTraining ASC");
        $stmt->execute();
        foreach($stmt->fetchAll() as $res)
            array_push($trainings, new Training($res["idTraining"]));
        return $trainings;
    }

    /**
     * Creates a new training in the database
     * @return int id of the new training
     */
    public static function new(
        string $start_date_time,
        string $end_date_time,
        int $location_id,
        int $max_participant_training,
        int $result_id = null
    ): int {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO training (
                startDateTimeTraining,
                endDateTimeTraining,
                Location_idLocation,
                maxParticipantTraining
            ) 
            VALUES (?,?,?,?)'
        );
        $stmt->bindValue(1, $start_date_time);
        $stmt->bindValue(2, $end_date_time);
        $stmt->bindValue(3, $location_id);
        $stmt->bindValue(4, $max_participant_training);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();

    }
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM training WHERE idTraining=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE training
            SET startDateTimeTraining=?,
            endDateTimeTraining=?,
            Location_idLocation=?,
            maxParticipantTraining=?
            WHERE idTraining=?'
        );
        $stmt->bindValue(1, $this->start_date_time);
        $stmt->bindValue(2, $this->end_date_time);
        $stmt->bindValue(3, $this->location_id);
        $stmt->bindValue(4, $this->max_participant_training);
        $stmt->bindValue(5, $this->id);
        $stmt->execute();
    }
}