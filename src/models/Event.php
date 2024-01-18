<?php

namespace Models;
use Database\DatabaseConnection;

class Event extends AbstractModel{
    private int $id;
    private string $start_date_time;
    private string $end_date_time;
    private string $name;
    private string $description;
    private array $images = array();

    private function __construct($id) {
        parent::__construct();
        $this->id = $id;
        $stmt = $this->getConnection()->prepare('SELECT * FROM event WHERE idEvent=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $event = $stmt->fetch();

        $this->start_date_time = $event["startDateTimeEvent"];
        $this->end_date_time = $event["endDateTimeEvent"];
        $this->name = $event["nameEvent"];
        $this->description = $event["descEvent"];

        // Fetches all the images of the event
        $stmt = $this->getConnection()->prepare('SELECT * FROM eventImage WHERE Event_idEvent=?');
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $images = $stmt->fetchAll();
        foreach($images as $image) $this->images[$image["idEventImage"]] = $image["blobImage"];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    public function setName(string $name): Event {
        $this->name = $name;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }
    public function setDescription(string $description): Event {
        $this->description = $description;
        return $this;
    }

    public function getEndDateTime() {
        return strtotime($this->end_date_time);
    }
    public function setEndDateTime(string $end_date_time): Event {
        $this->end_date_time = $end_date_time;
        return $this;
    }

    public function getStartDateTime() {
        return strtotime($this->start_date_time);
    }
    public function setStartDateTime(string $start_date_time): Event {
        $this->start_date_time = $start_date_time;
        return $this;
    }

    public function getImages() {
        return $this->images;
    }
    
    /**
     * Adds an image to the event
     * NOTE : does not require to call updateDatabase() 
     * As it instantly modifies the database
     * @param string $blob Image to add
     */
    public function addImage(string $blob): int {
        $stmt = $this->getConnection()->prepare(
            'INSERT INTO eventImage (
                blobImage,
                Event_idEvent
            ) 
            VALUES (?,?)'
        );
        $stmt->bindValue(1, $blob);
        $stmt->bindValue(2, $this->id);
        $stmt->execute();

        $inserted_id = $this->getConnection()->lastInsertId();
        $this->images[$inserted_id] = $blob;
        return $inserted_id;
    }

    /**
     * Removes an image from the event
     * NOTE : does not require to call updateDatabase() 
     * As it instantly modifies the database
     * @param int $id Id of the image to remove
     */
    public function removeImage(int $id) {
        $stmt = $this->getConnection()->prepare(
            'DELETE FROM eventImage WHERE idEventImage=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $this->images = array_diff($this->images, array($id));
    }

    /**
     * Checks if the event has expired (start date passed)
     * @return bool True if has expired, else false
     */
    public function hasExpired(): bool {
        return time() > strtotime($this->start_date_time);
    }

    /**
     * Checks if the event has passsed (start date)
     * @return bool True if has passsed, else false
     */
    public function hasPassed(): bool {
        return time() > strtotime($this->start_date_time);
    }

    public static function fetch($id): Event{
        return new Event($id);
    }

    /**
     * Returns all existing events
     * @return Event[] existing events
     */
    public static function fetchAll(): array {
        $events = array();
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare("SELECT idEvent FROM event ORDER BY startDateTimeEvent ASC");
        $stmt->execute();
        foreach($stmt->fetchAll() as $res)
            array_push($events, new Event($res["idEvent"]));
        return $events;
    }

    /**
     * Creates a new event in the database
     * @return int id of the new event
     */
    public static function new(
        string $name,
        string $description,
        string $start_date_time,
        string $end_date_time
    ): int {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'INSERT INTO event (
                nameEvent,
                descEvent,
                startDateTimeEvent,
                endDateTimeEvent
            ) 
            VALUES (?,?,?,?)'
        );
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $description);
        $stmt->bindValue(3, $start_date_time);
        $stmt->bindValue(4, $end_date_time);

        $stmt->execute();
        return $connection->getConnection()->lastInsertId();

    }
    public static function delete(int $id) {
        $connection = new DatabaseConnection();
        $stmt = $connection->getConnection()->prepare(
            'DELETE FROM event WHERE idEvent=?'
        );
        $stmt->bindValue(1, $id);
        $stmt->execute();
    }
    public function updateDatabase() {
        $stmt = $this->getConnection()->prepare(
            'UPDATE event
            SET nameEvent=?,
            descEvent=?,
            startDateTimeEvent=?,
            endDateTimeEvent=?
            WHERE idEvent=?'
        );
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, $this->description);
        $stmt->bindValue(3, $this->start_date_time);
        $stmt->bindValue(4, $this->end_date_time);
        $stmt->bindValue(5, $this->id);
        $stmt->execute();
    }
}