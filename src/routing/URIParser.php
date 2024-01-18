<?php

namespace Routing;

abstract class URIParser{
 
    /**
     * Parses the given URI by removing all trailing "/", all GET parameters 
     * and returns it
     * @param string $uri URI to parse
     * @return string Parsed URI
     */
    public static function parseURI(string $uri): string {
        /**
         * Regex that parses the uri by capturing until the first match of one the following case is met :
         * - '/?'
         * - '?'
         * - The last character of the string is '/'
         * If up to no match is found among the previous list of capture,
         * returns the whole uri, meaning that it was already parsed
         * 
         * e.g. 
         * Given the uris :
         * - /planning/view?id=2/
         * - /planning/view?id=2
         * - /planning/view/?id=2/
         * - /planning/view/?id=2
         * - /planning/view/
         * - /planning/view
         * Will always return : /planning/view
         */
        $URI_PARSER_REGEX = "/((.*)(?=(?:\/\?))|(.*)(?=\?)|(?:(.*)(?=\/$))|(?:(.*)))/";
        
        $matches = array();
        preg_match($URI_PARSER_REGEX, $uri, $matches);

        // Extracts the first capture group from the matches
        $request = $matches[1];

        return $request;
    }
}