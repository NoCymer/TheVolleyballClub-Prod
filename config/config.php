<?php
    if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/../');

    if ( !defined('CONTROLLERS') )
    define('CONTROLLERS', ABSPATH.'src/controllers'.'/');

    if ( !defined('TEMPLATES') )
    define('TEMPLATES', ABSPATH.'templates'.'/');

    if ( !defined('PUBLIC_PATH') )
    define('PUBLIC_PATH', ABSPATH.'public'.'/');
    

    /**
     * Gets a file from the public repository
     * @param string $file Path of the file from the public directory
     * @return string Content of the file
     */
    function get_public_file($file) {
        return file_get_contents(PUBLIC_PATH.$file);
    }

    session_start();
?>