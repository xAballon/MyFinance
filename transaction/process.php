<?php

include('check_login.php');
require_once('dbConnection.php');


if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['type']) {

    $type = $_POST['type'];

    switch($type){
        case 'eingang':
            //Skript für Eingang
            break;
        case 'ausgang':
            //Skript für Ausgang
            break;
        case 'transfer':
            //Skript für Transfer
            break;
        default:
        echo "ungültiger Transaktionstyp";
    }



}else{
    echo "Fehler bei der Datenübertragungsmethode<br><br><a href='index.php'>Index</a>";
}

?>