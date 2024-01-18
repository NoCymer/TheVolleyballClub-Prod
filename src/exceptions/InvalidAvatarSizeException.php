<?php

namespace Exceptions;

class InvalidAvatarSizeException extends DisplayableException {
    public function __construct(){
        parent::__construct("avatar-size-invalid");
    }
}