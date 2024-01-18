<?php

namespace Exceptions;

class InvalidPasswordException extends DisplayableException {
    public function __construct(){
        parent::__construct("invalid-password");
    }
}