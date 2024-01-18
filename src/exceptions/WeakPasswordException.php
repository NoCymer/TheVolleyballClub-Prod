<?php

namespace Exceptions;

class WeakPasswordException extends DisplayableException {
    public function __construct(){
        parent::__construct("weak-password");
    }
}