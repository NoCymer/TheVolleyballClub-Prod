<?php

namespace Validation;

use Exceptions\InvalidDocumentTypeException;

class DocumentValidator {
    private static $valid_doc_types = ["application/pdf", "image/jpeg", "image/jpg", "image/png", "image/jpeg"];
    
    /**
     * Verifies that the document type is valid
     * @param string $document_type type of the image
     * @throws InvalidDocumentTypeException If document is invalid
     */
    public static function checkDocumentType(string $document_type) {
        foreach(DocumentValidator::$valid_doc_types as $valid_doc_type) if($valid_doc_type == $document_type) return;
        throw new InvalidDocumentTypeException();
    }
}