<?php

namespace Exceptions;

class InvalidBirthdateException extends DisplayableException {
    public function __construct(){
        parent::__construct("birthdate-invalid");
    }
}