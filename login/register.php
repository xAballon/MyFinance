<?php

$vorname = htmlspecialchars(trim($_POST['vName']));
$nachname = htmlspecialchars(trim($_POST['nName']));
$email = htmlspecialchars(trim($_POST['email']));
$pass = htmlspecialchars(trim($_POST['pass']));
$confirmPass = htmlspecialchars(trim($_POST['conPass']));

require_once('../Misc/dbConnection.php');

//Formular auf Vollständigkeit überprüfen
if (isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($vorname) && !empty($nachname) && !empty($email) && !empty($pass) && !empty($confirmPass)) {

        // Stimmen Passwörter überein?
        if ($pass !== $confirmPass) {
            die("Die Passwörter stimmen nicht überein. Bitte versuche es erneut.<br><br><a href='login.php'>Zurück zur Anmeldung</a>");
        }

        // PW 8 Zeichen Lang?
        if (strlen($pass) < 8) {
            die("Das Passwort muss mindestens 8 Zeichen lang sein.<br><br><a href='login.php'>Zurück zur Anmeldung</a>");
        }

        //E-Mail vergeben?
        $stmt = $pdo->prepare("SELECT COUNT(email) FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetchColumn();
        //Anmeldeformular
        if ($row > 0) {
            die("Benutzer mit dieser E-Mail bereits vorhanden.<br><br><a href='login.php'>Zurück zur Anmeldung</a>");
        }

        // Passwort verschlüsseln
        $passHash = password_hash($pass, PASSWORD_ARGON2I);

        //Benutzer Speichern
        try {
            $stmt = $pdo->prepare("INSERT INTO user (vorname, nachname, email, passwort) VALUES (:vorname, :nachname, :email, :passwort)");
            $stmt->execute([
                ':vorname' => $vorname,
                ':nachname' => $nachname,
                ':email' => $email,
                ':passwort' => $passHash,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            die("Ein Fehler ist aufgetreten :(
            <br><br><a href='login.php'>Zurück zur Anmeldung</a>");
        }

?>
        <!--Daten für die Weiterleitung vorbereiten-->
        <form id="verify" action="verify.php" method="POST">
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="pass" value="<?= $pass ?>">

        </form>

        <script>
            // Formular automatisch abschicken
            document.getElementById('verify').submit();
        </script>

<?php

    } else {
        die("Bitte füllen Sie das Anmeldeformular Korrekt aus.<br><br><a href='login.php'>Zurück zur Anmeldung</a>");
    }
} else {
    die("Unauthorized :(<br><br><a href='login.php'>Zurück zur Anmeldung</a>");
}
