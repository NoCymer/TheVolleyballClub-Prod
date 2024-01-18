<?php

namespace Exceptions;

class InvalidGroupException extends DisplayableException {
    public function __construct(){
        parent::__construct("group-invalid");
    }
}