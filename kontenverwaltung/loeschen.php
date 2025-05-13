<?php 
include('../header.php');
require_once('../misc/dbConnection.php'); // Verbindung zur Datenbank herstellen

// Prüfen, ob knr übergeben wurde
if (!isset($_GET['knr']) || !is_numeric($_GET['knr'])) {
    die("Ungültige Kontonummer.");
}

$knr = (int)$_GET['knr'];
try {
    // Transaktion starten
    $pdo->beginTransaction();

    // Überprüfen, ob das Konto existiert
    $stmt = $pdo->prepare("SELECT * FROM konto WHERE knr = ? AND uid = ?");
    $stmt->execute([$knr, $_SESSION['user_id']]);
    $konto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$konto) {
        $pdo->rollBack();
        die("Konto nicht gefunden.");
    }

    $kid = $konto['kid']; // Jetzt sicher, dass $konto existiert

    // 1. Transaktionen löschen, die mit dem Konto verbunden sind
    $stmt = $pdo->prepare("DELETE FROM transaktionen WHERE quelle = :kid OR ziel = :kid");
    $stmt->execute([':kid' => $kid]);

    // 2. Konto selbst löschen
    $stmt = $pdo->prepare("DELETE FROM konto WHERE kid = ?");
    $stmt->execute([$kid]);

    // Transaktion abschließen
    $pdo->commit();

    // Weiterleitung zurück zur Übersicht
    echo '<script>window.location.href = "kontenverwaltung.php";</script>';
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Fehler beim Löschen: " . $e->getMessage());
}

include('../footer.php');
?>