<?php
require './vendor/autoload.php';
include_once("/srv/http/endpoint/config/config.php");

use Routing\MainRouter;
use Routing\URIParser;

(new MainRouter())->handleRequest(URIParser::parseURI($_SERVER['REQUEST_URI']));