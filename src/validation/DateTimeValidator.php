<?php

namespace Validation;

use Exceptions\InvalidDateException;

class DateTimeValidator {

    private string $DATE_VALIDATION_REGEX = "/^(\d{4})-(\d{2})-(\d{2})[T ]{1}(\d{2}):(\d{2}):(\d{2})$/";

    /**
     * Verifies if the datetime is valid
     * @param string $datetime Datetime to check
     * @throws InvalidDateException If date invalid
     */
    public static function checkDateTimeValidity(string $datetime) {
        $d = strtotime($datetime);
        // Check if the datetime is valid and matches the expected format
        if(!($d && date('Y-m-d H:i:s', $d) === $datetime)) {
            throw new InvalidDateException();
        }
    }
} 