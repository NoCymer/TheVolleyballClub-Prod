<?php

namespace Optimizations;

class ImageCompressor {

    /**
     * Compresses a given image to reasonable size
     * @param string $image_blob Image to compress
     * @return string Compressed image blob
     */
    public static function compress($image_blob) {
        $img_size = strlen($image_blob) / 1024; // size in KB

        // Starts conversion & compression to JPEG
        $image = imagecreatefromstring($image_blob);
        ob_start();

        // If image larger than 2 MB compress it by 70%
        if($img_size >= 2000) {
            imagejpeg($image,NULL, 30);
        } 
        // If image larger than 1 MB compress it by 50 %
        else if($img_size >= 1000) {
            imagejpeg($image,NULL, 50);
        }
        // If image larger than 500 KB compress it by 20 %
        else if($img_size >= 500) {
            imagejpeg($image,NULL, 80);
        }
        // Else dont compress, just convert to jpeg
        else {
            imagejpeg($image,NULL, 100);
        }

        // Ends conversion and compression
        $compressed_image_blob=ob_get_contents();
        ob_end_clean();

        return $compressed_image_blob;
    }
}