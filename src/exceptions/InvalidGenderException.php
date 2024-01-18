<?php

namespace Exceptions;

class InvalidGenderException extends DisplayableException {
    public function __construct(){
        parent::__construct("gender-invalid");
    }
}