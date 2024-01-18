<?php

namespace Exceptions;

class InvalidDateException extends DisplayableException {
    public function __construct(){
        parent::__construct("date-invalid");
    }
}