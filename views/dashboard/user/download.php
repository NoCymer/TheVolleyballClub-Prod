<?php

use Models\IdCard;
use Models\MedicalCertificate;
use Models\User;

function handleRequest() {
    if(!isset($_SESSION["adminConnect"]) && $_GET["user"] != $_SESSION["userConnect"])
        return; 
    
    $user = User::fetch($_GET["user"]);
    $document = null;
    switch($_GET["document"]) {
        case 'idCard':
            $document = IdCard::fetch($user->getIdCardId());
            break;
        case 'medicalCertificate':
            $document = MedicalCertificate::fetch($user->getMedicalCertificateId());
            break;
    }
    
    $blob = $document->getDocument();
    $size = strlen($blob);
    $type = $document->getType();
    $file_name = $document->getFileName();
    
    header("Content-length: $size");
    header("Content-type: $type");
    header("Content-Disposition: attachment; filename=$file_name");

    echo $blob;
}

handleRequest();