<?php

namespace Controllers;

interface IRequestHandler {
    /**
     * Handles the request of the page
     */
    public static function handleRequest();

    /**
     * Redirects to another page after handling
     */
    public static function redirect();
}