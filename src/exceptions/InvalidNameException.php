<?php

namespace Exceptions;

class InvalidNameException extends DisplayableException {
    public function __construct(){
        parent::__construct("name-invalid");
    }
}