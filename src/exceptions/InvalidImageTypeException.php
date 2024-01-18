<?php

namespace Exceptions;

class InvalidImageTypeException extends DisplayableException {
    public function __construct(){
        parent::__construct("image-type-invalid");
    }
}