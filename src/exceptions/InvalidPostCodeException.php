<?php

namespace Exceptions;

class InvalidPostCodeException extends DisplayableException {
    public function __construct(){
        parent::__construct("invalid-post-code");
    }
}