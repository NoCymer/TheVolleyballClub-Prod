<?php
    if(isset($_GET["competition_id"])) {
        include_once("competition.php");
    } 
    else if (isset($_GET["training_id"])) {
        include_once("training.php");
    }
    else if (isset($_GET["event_id"])) {
        include_once("event.php");
    }
    else {
        header("Location: /planning");
    }
    