<?php
include('misc/check_login.php');
require_once('misc/dbConnection.php');

$uid = $_SESSION['user_id'];

// Transaktionen löschen
$stmt = $pdo->prepare('DELETE FROM transaktionen WHERE uid = :uid');
$stmt->execute([':uid' => $uid]);

// Konten löschen
$stmt = $pdo->prepare('DELETE FROM konto WHERE uid = :uid');
$stmt->execute([':uid' => $uid]);

// Benutzer löschen
$stmt = $pdo->prepare('DELETE FROM user WHERE uid = :id');
$stmt->execute([':id' => $uid]);

// Session beenden
session_destroy();

header('Location: ../index.php');
exit;
?>
