<?php

namespace Models;
use Database\DatabaseConnection;

class Competition extends AbstractModel{
    private int $id;
    private string $start_date_time;
    private string $end_date_time;
    private int | null $result_id;
    private int $location_id;
    private int $max_participant_competition; 	
    private array $participants = array();

    private static int $MAX_TIME_BEFORE_INSCRIPTION = 86400; // in seconds

    private function __construct($id) {
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM competition WHERE idCompetition=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $competition = $stmt->fetch();

        $this->start_date_time = $competition["startDateTimeCompetition"];
        $this->end_date_time = $competition["endDateTimeCompetition"];
        $this->result_id = $competition["Result_idResult"];
        $this->location_id = $competition["Location_idLocation"];
        $this->max_participant_competition = $competition["maxParticipantCompetition"];

        // Fetches all the users participating to the competition
        $stmt = $this->getConnection()->prepare('SELECT * FROM user_has_competition WHERE Competition_idCompetition=? AND validation="1"');
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
            'INSERT INTO user_has_competition 
            (User_idUser, Competition_idCompetition)
            VALUES (?,?)'
        );
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();

        return true;
    }

    /**
     * Sets if the given user's application was either validated or invalidated
     * @param bool $validation If true, application is valid, else false
     * @param int $id Id of the user
     */
    public function setParticipationValidation(int $id, bool $validation) {
        if($this->hasExpired()) return false;

        $stmt = $this->getConnection()->prepare(
            'UPDATE user_has_competition 
            SET `validation`=b?
            WHERE User_idUser=? AND Competition_idCompetition=?'
        );
        $stmt->bindValue(1, $validation);
        $stmt->bindValue(2, $id);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();

        // Adds the participant to the participants array
        array_push($this->participants, $id);
    }

    /**
     * Returns a list of user'ids that want to participate
     * to the competition but the application wasn't reviewed yet
     * @return int[] Array of user id
     */
    public function getAllUnreviewedApplications() {
        if($this->hasExpired()) return array();

        $stmt = $this->getConnection()->prepare(
            'SELECT User_idUser FROM user_has_competition WHERE validation IS NULL AND Competition_idCompetition=?'
        );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $ret = array();
        foreach($stmt->fetchAll() as $entry) array_push($ret, $entry["User_idUser"]);
        return $ret;
    }

    /**
     * Returns all ids of the participants
     * @return int[]
     */
    public function getParticipantsIds() {
        return $this->participants;
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
            'DELETE FROM user_has_competition
            WHERE User_idUser=? AND Competition_idCompetition=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();

        // Removes the participant from the participants array
        $this->participants = array_diff($this->participants, array($id));
        return true;
    }

    /**
     * Returns the number of places left in the competition
     * @return int Places left in the competition
     */
    public function getPlacesLeft(): int {
        return $this->max_participant_competition - count($this->participants);
    }

    /**
     * Checks if the competition has expired (start date - $MAX_TIME_BEFORE_INSCRIPTION passed)
     * @return bool True if has expired, else false
     */
    public function hasExpired(): bool {
        return time() > (strtotime($this->start_date_time) - Competition::$MAX_TIME_BEFORE_INSCRIPTION);
    }

    /**
     * Checks if the competition has passsed (start date)
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


    public static function fetch($id): Competition{
        return new Competition($id);
    }

    /**
     * Returns all existing competitions
     * @return Competition[] existing competitions
     */
    public static function fetchAll(): array {
        $competitions = array();
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare("SELECT idCompetition FROM competition ORDER BY startDateTimeCompetition ASC");
        $stmt->execute();
        foreach($stmt->fetchAll() as $res)
            array_push($competitions, new Competition($res["idCompetition"]));
        return $competitions;
    }

    /**
     * Creates a new competition in the database
     * @return int id of the new competition
     */
    public static function new(
        string $start_date_time,
        string $end_date_time,
        int $location_id,
        int $max_participant_competition,
        int $result_id = null
    ): int {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO competition (
                startDateTimeCompetition,
                endDateTimeCompetition,
                Result_idResult,
                Location_idLocation,
                maxParticipantCompetition
            ) 
            VALUES (?,?,?,?)'
        );
        $stmt->bindValue(1, $start_date_time);
        $stmt->bindValue(2, $end_date_time);
        $stmt->bindValue(3, $result_id);
        $stmt->bindValue(4, $location_id);
        $stmt->bindValue(5, $max_participant_competition);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();

    }
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM competition WHERE idCompetition=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE competition
            SET startDateTimeCompetition=?,
            endDateTimeCompetition=?,
            Result_idResult=?,
            Location_idLocation=?,
            maxParticipantCompetition=?
            WHERE idCompetition=?'
        );
        $stmt->bindValue(1, $this->start_date_time);
        $stmt->bindValue(2, $this->end_date_time);
        $stmt->bindValue(3, $this->result_id);
        $stmt->bindValue(4, $this->location_id);
        $stmt->bindValue(5, $this->max_participant_competition);
        $stmt->bindValue(6, $this->id);
        $stmt->execute();
    }
}