<?php

namespace Exceptions;

class EmailFormatException extends DisplayableException {
    public function __construct(){
        parent::__construct("email-format-invalid");
    }
}