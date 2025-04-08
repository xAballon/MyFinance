<?php
session_start();
require_once('../Misc/dbConnection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']));
    $pass = htmlspecialchars(trim($_POST['pass']));


    //Benutzer suchen
    $stmt = $pdo->prepare("SELECT uid, passwort FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user) {

        //Auf Rehash überprüfen + rehash
        if (password_needs_rehash($user['passwort'], PASSWORD_DEFAULT)) {
            var_dump($user['passwort']);
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            var_dump($hash, $user['uid']);
            $statement = $pdo->prepare("UPDATE user SET passwort = :new_pass where uid = :uid");
            $statement->bindParam(':new_pass', $hash);
            $statement->bindParam(':uid', $user['uid']);
            $statement->execute();

            //Benutzer mit gehashtem Passwort erneut abfragen
            $stmt = $pdo->prepare("SELECT uid, passwort FROM user WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
        }

        //Passwort verifizieren
        if (password_verify($pass, $user['passwort'])) {
            $_SESSION['user_id'] = $user['uid'];
            header("Location: index.php");
        } else {
            echo "Ungültige Anmeldedaten.<br> <a href='login.php'>Zurück zum Login</a>";
        }
        $_SESSION['user_id'] = $user['uid'];
    } else {
        die("Fehler bei den Anmeldedaten ist Aufgetreten!<br><br><a href='login.php'>Zurück zum Login</a>");
    }
}
