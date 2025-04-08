<?php

include('check_login.php');

if ($_GET['submit'] && $_GET['type']) {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $type = $_GET['type'];

        switch ($type) {
            case 'eingang':
                //Formular für Eingang
                break;
            case 'ausgang':
                //Formular für Ausgang
                break;
            case 'transfer':
                //Formular für Transfer
                break;
            default:
                echo "Ungültiger Formulartyp, oder Typ nicht vorhanden :(";
        }
    } else {
        echo "Ungültiger Formulartyp, oder Typ nicht vorhanden :(";
    }
} else {
?>



<?php
}
?>