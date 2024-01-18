<?php

namespace Exceptions;

class InvalidDocumentSizeException extends DisplayableException {
    public function __construct(){
        parent::__construct("document-size-invalid");
    }
}