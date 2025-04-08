<?php
//Prüfung auf Login
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: '. $_SERVER['DOCUMENT_ROOT'].'/Login/login.php');
}
?>