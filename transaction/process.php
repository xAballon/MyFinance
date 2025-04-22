<?php

include('../misc/check_login.php');
require_once('../misc/dbConnection.php');


if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['type']) {

    switch($_POST['type']){
        case 'eingang':
            // !Achtung fester Wert (nicht optimal)
            $quelle = 000; //Standard Einnahmen Konto
            $betrag = trim(htmlspecialchars($_POST['betrag']));
            $zielKnr = trim(htmlspecialchars($_POST['konto']));
            $kommentar = trim(htmlspecialchars($_POST['kommentar']));
            break;
        case 'ausgang':
            $zielKnr = 999; //Standard Ausgaben Konto
            $quelle = trim(htmlspecialchars($_POST['konto']));
            $betrag = trim(htmlspecialchars($_POST['betrag']));
            $kommentar = trim(htmlspecialchars($_POST['kommentar']));
            break;
        case 'transfer':
            //Skript für Transfer
            break;
        default:
        echo "ungültiger Transaktionstyp";
        die();
    }


    $uid = $_SESSION['user_id'];  

    $stmt = $pdo->prepare("SELECT MAX(tnr) FROM transaktionen WHERE uid = :uid");
    $stmt->execute([':uid' => $uid]);
    $max = $stmt->fetchColumn();

    $tnr = $max ? $max + 1 : 1;

    $stmt = $pdo->prepare("SELECT kid FROM konto WHERE knr = 999 AND uid = :uid");
    $stmt->execute([':uid' => $uid]);
    $quelle = $stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT kid FROM konto WHERE knr = :konto AND uid = :uid");
    $stmt->execute([':uid' => $uid, ':konto' => $zielKnr]);
    $ziel = $stmt->fetchColumn();

    $stmt = $pdo->prepare('INSERT INTO transaktionen (betrag, tnr, kommentar, quelle, ziel, uid)
    VALUES (:betrag, :tnr, :kommentar, :quelle, :ziel, :uid)');
    $stmt->execute([
        ':betrag' => $betrag,
        ':tnr' => $tnr,
        ':kommentar' => $kommentar,
        ':quelle' => $quelle,
        ':ziel' => $ziel,
        ':uid' => $uid
    ]);


}else{
    echo "Fehler bei der Datenübertragungsmethode<br><br><a href='index.php'>Index</a>";
}

?>