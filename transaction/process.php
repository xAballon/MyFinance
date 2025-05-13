<?php

include('../misc/check_login.php');
require_once('../misc/dbConnection.php');


if($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['type']) {

    $uid = $_SESSION['user_id'];  
    switch($_POST['type']){
        case 'eingang':
            // !Achtung fester Wert (nicht optimal)
            $quelle = 000;
            $betrag = trim(htmlspecialchars($_POST['betrag']));
            $ziel = trim(htmlspecialchars($_POST['konto']));
            $kommentar = trim(htmlspecialchars($_POST['kommentar']));
            break;
        case 'ausgang':
            $ziel = 999;            
            $quelle = trim(htmlspecialchars($_POST['konto']));
            $betrag = trim(htmlspecialchars($_POST['betrag']));
            $kommentar = trim(htmlspecialchars($_POST['kommentar']));
            break;
        case 'transfer':

            $quelle = trim(htmlspecialchars($_POST['quelle']));
            $ziel = trim(htmlspecialchars($_POST['ziel']));   
            $betrag = trim(htmlspecialchars($_POST['betrag']));
            $kommentar = trim(htmlspecialchars($_POST['kommentar']));
            break;
            default:
            echo "ungültiger Transaktionstyp";
            die();
        }
        
        
        
    $stmt = $pdo->prepare("SELECT MAX(tnr) FROM transaktionen WHERE uid = :uid");
    $stmt->execute([':uid' => $uid]);
    $max = $stmt->fetchColumn();

    $tnr = $max ? $max + 1 : 1;

    //Konto-ID zu Kontonummern finden
    $stmt = $pdo->prepare("SELECT kid FROM konto WHERE knr = :qknr AND uid = :uid");
    $stmt->execute([':uid' => $uid, ':qknr' => $quelle]);
    $quelle = $stmt->fetchColumn();
    
    $stmt = $pdo->prepare("SELECT kid FROM konto WHERE knr = :konto AND uid = :uid");
    $stmt->execute([':uid' => $uid, ':konto' => $ziel]);
    $ziel = $stmt->fetchColumn();

    //Transaktion erstellen
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


    // Kontostand aktualisieren
$pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :ziel")->execute([':betrag' => $betrag, ':ziel' => $ziel]);
if($_POST['type'] == 'eingang'){
    $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :quelle")->execute([':betrag' => $betrag, ':quelle' => $quelle]);
}else{
    $pdo->prepare("UPDATE konto SET kontostand = kontostand - :betrag WHERE kid = :quelle")->execute([':betrag' => $betrag, ':quelle' => $quelle]);
}

header('Location: ../dashboard.php');

}else{
    echo "Fehler bei der Datenübertragungsmethode<br><br><a href='index.php'>Index</a>";
}

?>