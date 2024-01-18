<?php

namespace Validation;

use Exceptions\InvalidImageTypeException;

class ImageValidator {
    private static $valid_img_types = ["image/png", "image/jpeg", "image/webp", "image/jpg"];
    
    /**
     * Verifies that the image type is valid
     * @param string $image_type type of the image
     * @throws InvalidImageTypeException If image is invalid
     */
    public static function checkImageType(string $image_type) {
        foreach(ImageValidator::$valid_img_types as $valid_img_type) if($valid_img_type == $image_type) return;
        throw new InvalidImageTypeException();
    }
}