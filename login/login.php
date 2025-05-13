<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyFinance | Login</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>

<?php
include "../header.php";
require_once('../misc/dbConnection.php');

if (isset($_POST['submit']) && !empty($_POST['email'])) {

    $email = htmlspecialchars(trim($_POST['email']));

    $stmt = $pdo->prepare("SELECT COUNT(email) FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);

    $row = $stmt->fetchColumn();

    if ($row > 0) {
?>

        <body>
            <div class="login-container">
                <h2>Willkommen bei MyFinance</h2>
                <p>Nutzer mit dieser E-Mail gefunden.<br>Anmelden:</p>

                <form action="verify.php" method="post">
                    <table class="login-form-table">
                        <tr>
                            <td><label for="email">E-Mail:</label></td>
                            <td><input class="login-input" type="email" name="email" value="<?= $email ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="pass">Passwort:</label></td>
                            <td><input class="login-input" type="password" name="pass" required></td>
                        </tr>
                    </table>
                    <input class="login-submit" type="submit" name="submit" value="Anmelden">
                </form>
            </div>
        </body>

    </html>

<?php
    } else {
?>

        <body>
            <div class="login-container">
                <h2>Willkommen bei MyFinance</h2>
                <p>Ihre E-Mail wurde noch nicht registriert.<br>Neuen Benutzer anlegen:</p>

                <form action="register.php" method="post">
                    <table class="login-form-table">
                        <tr>
                            <td><label for="email">E-Mail:</label></td>
                            <td><input class="login-input" type="email" name="email" value="<?= $email ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="vorname">Vorname:</label></td>
                            <td><input class="login-input" type="text" name="vName" required></td>
                        </tr>
                        <tr>
                            <td><label for="nachname">Nachname:</label></td>
                            <td><input class="login-input" type="text" name="nName" required></td>
                        </tr>
                        <tr>
                            <td><label for="pass">Passwort:</label></td>
                            <td><input class="login-input" type="password" name="pass" minlength="8" maxlength="64" required></td>
                        </tr>
                        <tr>
                            <td><label for="conPass">Passwort wiederholen:</label></td>
                            <td><input class="login-input" type="password" name="conPass" minlength="8" maxlength="64" required></td>
                        </tr>
                    </table>
                    <input class="login-submit" type="submit" name="submit" value="Registrieren">
                </form>
            </div>
        </body>

    </html>

<?php
    }
} else {
?>

    <body>
        <div class="login-container">
            <h2>Willkommen bei MyFinance</h2>
            <p>Geben Sie Ihre E-Mail-Adresse ein. Wenn Sie bereits ein Konto haben, können Sie sich anmelden. Andernfalls legen Sie ein neues Benutzerkonto an.</p>

            <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
                <table class="login-form-table">
                    <tr>
                        <td><label for="email">E-Mail:</label></td>
                        <td><input class="login-input" type="email" name="email" required></td>
                    </tr>
                </table>
                <input class="login-submit" type="submit" name="submit" value="Weiter">
            </form>
        </div>
    </body>

    </html>

<?php
include "../footer.php";
}
?>
