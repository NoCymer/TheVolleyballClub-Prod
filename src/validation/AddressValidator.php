<?php

namespace Validation;

use Exceptions\InvalidPostCodeException;

class AddressValidator {
    
    private static string $POST_CODE_REGEX = "/^[0-9]{5}$/";

    /**
     * Verifies if the post code is valid
     * @param string $post_code Post code to check
     * @throws InvalidPostCodeException If post code invalid
     */
    public static function checkPostCodeValidity(string $post_code) {
        if(preg_match(AddressValidator::$POST_CODE_REGEX, $post_code) == 0) 
            throw new InvalidPostCodeException();
    }
}