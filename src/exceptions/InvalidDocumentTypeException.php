<?php

namespace Exceptions;

class InvalidDocumentTypeException extends DisplayableException {
    public function __construct(){
        parent::__construct("document-type-invalid");
    }
}