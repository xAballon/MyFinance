<?php
include('../misc/check_login.php');
require_once('../misc/dbConnection.php');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyFinance | Ausgang erfassen</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>

<header>
    <?php include('../header.php'); ?>
</header>

<div class="myfinance-container">
  <h1>Geldausgang erfassen</h1>

  <form class="myfinance-form" action="process.php" method="POST">
    <label for="konto">Konto</label>
    <select name="konto" required>
      <?php
        $stmt = $pdo->prepare('SELECT knr, bezeichnung FROM konto WHERE uid = :id AND knr BETWEEN 100 AND 899');
        $stmt->execute([':id' => $_SESSION['user_id']]);
        foreach($stmt->fetchAll() as $konto){
          echo "<option value='{$konto['knr']}'>{$konto['bezeichnung']}</option>";
        }
      ?>
    </select>

    <label for="betrag">Betrag</label>
    <input type="number" id="betrag" name="betrag" placeholder="0,00 €" step="0.01" min="0.01" required>

    <label for="kommentar">Kommentar</label>
    <textarea name="kommentar" rows="3"></textarea>

    <input type="hidden" name="type" value="ausgang">

    <input type="submit" value="Speichern">
  </form>
</div>

<footer>
    <?php include('../footer.php'); ?>
</footer>

</body>
</html>
