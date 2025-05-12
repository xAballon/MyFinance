<?php
include('misc/check_login.php');
require_once('misc/dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('UPDATE user SET vorname = :vorname, nachname = :nachname WHERE uid = :id');
    $stmt->execute([
        ':vorname' => $_POST['vorname'],
        ':nachname' => $_POST['nachname'],
        ':id' => $_SESSION['user_id']
    ]);
    header('Location: profile.php?success=1');
    exit;
}
?>
