<?php

include('../misc/check_login.php');

if ($_GET['type']) {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $type = $_GET['type'];

        switch ($type) {
            case 'eingang':
                include('eingang.php');
                break;
            case 'ausgang':
                include('ausgang.php');
                break;
            case 'transfer':
                include('transfer.php');
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