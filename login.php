<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<?php
require_once('dbConnection.php');

//Prüfung ob E-Mail bereits regristriert ist
if (isset($_POST['submit']) && !empty($_POST['email'])) {

    $email = htmlspecialchars(trim($_POST['email']));

    $stmt = $pdo->prepare("SELECT COUNT(email) FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);


    $row = $stmt->fetchColumn();
    //Anmeldeformular
    if ($row > 0) {
        //echo "//Nutzer existiert bereits";
?>

        <body>
            <h2>Wilkommen Bei MyFinance</h2>
            <p>Nutzer mit dieser E-Mail gefunden.<br>Anmelden:</p>

            <form action="verify.php" method="post" class="login">
                <table>
                    <tr>
                        <td>
                            <label for="email">Email: </label>
                        </td>
                        <td>
                            <input type="email" name="email" value="<?= $email ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="pass">Passwort: </label>
                        </td>
                        <td>
                            <input type="password" name="pass" required>
                        </td>
                    </tr>

                </table>
                <input type="submit" name="submit">
            </form>

        </body>

</html>

<?php
        //Regristrierformular
    } else {
        // echo "//Nutzer existiert noch nicht";
?>

    <body>
        <h2>Willkommen bei MyFinance</h2>
        <p>Ihre E-Mail wurde noch nicht regristriert.<br>Einen Neuen Benutzer anlegen:</p>


        <form action="register.php" method="post" class="login">
            <table>
                <tr>
                    <td>
                        <label for="email">E-Mail: </label>
                    </td>
                    <td>
                        <input type="email" name="email" value="<?= $email ?>" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="vorname">Vorname: </label>
                    </td>
                    <td>
                        <input type="text" name="vName" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="nachname">Nachname: </label>
                    </td>
                    <td>
                        <input type="text" name="nName" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="pass">Passwort: </label>
                    </td>
                    <td>
                        <input type="password" name="pass" min="8" max="64" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="pass">Passwort wiederholen: </label>
                    </td>
                    <td>
                        <input type="password" name="conPass" min="8" max="64" required>
                    </td>
                </tr>
            </table>
            <input type="submit" name="submit">
        </form>

        <a href=""></a>
    </body>

    </html>


<?php
    }
} else {

    //E-Mail eingabe
?>

<body>
    <h2>Wilkommen Bei MyFinance</h2>
    <p>Geben Sie hier Ihre E-Mail ein. Sollten sie bereits einen Account haben, können Sie sich direkt anmelden. Anderenfalls können Sie einen Neuen Benutzer anlegen.</p>


    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" class="login">
        <table>
            <tr>
                <td>
                    <label for="email">Email: </label>
                </td>
                <td>
                    <input type="email" name="email" required>
                </td>
            </tr>
        </table>
        <input type="submit" name="submit">
    </form>

</body>

</html>

<?php
}
?>