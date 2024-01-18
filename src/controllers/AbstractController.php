<?php

namespace Controllers;

/**
 * Abstract controller 
 */
abstract class AbstractController {

    /**
     * Creates an entity from the POST data
     */
    abstract public static function new();

    /**
     * Deletes an entity having its id specified in the POST data
     */
    abstract public static function delete();

    /**
     * Updates an entity with the POST data
     */
    abstract public static function update();

    /**
     * Validates the POST data,
     * throws exception if any field is invalid 
     */
    abstract protected static function validate();
}