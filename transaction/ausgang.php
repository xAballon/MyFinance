<?php

include('../misc/check_login.php');
require_once('../misc/dbConnection.php');
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ausgang</title>
</head>

<body>
    <h1>Geldausgang erfassen</h1>

    <form action="process.php" method="POST">
        <label for="Konto">Konto: </label>
        
        <select name="konto" required>
            <?php
            $stmt = $pdo->prepare('SELECT knr, bezeichnung from konto WHERE uid=:id AND knr BETWEEN 100 AND 899');
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $konten = $stmt->fetchAll();

            echo var_dump($konten);

            foreach($konten as $konto){
                echo "<option name='konto' value=" . $konto['knr'] . ">" . $konto['bezeichnung'] . "</option>";
                echo var_dump($konten);
            }
            
            ?>
        </select>

        <br><br>
        <!-- WICHTIG Noch Keine Überprüfung ob Betrag möglich ist -->
        <label for="betrag">Betrag: </label>
        <input type="number" id="betrag" name="betrag" placeholder="0,00 €" step="0.01" min="0.01" requiured>

        <br><br>
        <label for="kommentar">Kommentar: </label>
        <textarea name="kommentar" rows="3" cols="30"></textarea>

        <input type="hidden" name="type" value="ausgang">

        <br><br>
        <input type="submit" value="Speichern">
    </form>


</body>


</html>