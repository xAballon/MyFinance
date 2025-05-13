<?php
include('../misc/check_login.php');
include('../misc/dbConnection.php');

// Konten zwischen 100 und 899 laden
$stmt = $pdo->prepare("SELECT * FROM konto WHERE knr BETWEEN 100 AND 899 AND uid = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$konten = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>MyFinance | Kontenverwaltung</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #24d1c2; }
        .actions { text-align: center; }
        .add-btn { display: block; margin: 20px auto; text-align: center; }
    </style>
</head>
<body>
    <header>
        <?php include('../header.php'); ?>
    </header>

<h2 style="text-align:center;">Kontenverwaltung</h2>

<table>
    <thead>
        <tr>
            <th>Kontonummer</th>
            <th>Bezeichnung</th>
            <th>Kontostand</th>
            <th>Aktion</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($konten as $konto): ?>
        <tr>
            <td><?= htmlspecialchars($konto['knr']) ?></td>
            <td><?= htmlspecialchars($konto['bezeichnung']) ?></td>
            <td><?= number_format($konto['kontostand'], 2, ',', '.') ?> €</td>
            <td class="actions">
                <a href="loeschen.php?knr=<?= $konto['knr'] ?>" style="text-decoration: none; color: white;"
                   onclick="return confirm('Willst du das Konto \ <?php addslashes($konto['bezeichnung']) ?>\ wirklich löschen?');">
                   Löschen 
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div  style="text-align:center; padding-top: 50px;">
    <a href="hinzufuegen.php"  class="myf-button">+ Konto hinzufügen</a>
</div>

</body>
</html>

<?php include('../footer.php'); ?>