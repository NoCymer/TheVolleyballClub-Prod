<?php

namespace Exceptions;

class EmailAlreadyExistsException extends DisplayableException {
    public function __construct(){
        parent::__construct("email-already-exist");
    }
}