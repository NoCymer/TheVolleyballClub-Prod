<?php

namespace Exceptions;

class InvalidSurnameException extends DisplayableException {
    public function __construct(){
        parent::__construct("surname-invalid");
    }
}