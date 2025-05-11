    <?php
include('misc/check_login.php');
include('misc/dbConnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontenübersicht</title>
</head>
<body>
    
<h1>Kontenübersicht</h1>

<table>
    
  <thead>
    <tr>
      <th>Konto</th>
      <th>Kontostand</th>

      <td></td>
    </tr>
  </thead>
  <tbody>
    <?php
$stmt = $pdo->prepare('SELECT knr, bezeichnung, kontostand FROM Konto WHERE uid = :uid');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$konten = $stmt->fetchAll();

foreach($konten as $row){
    echo '<a href="http://localhost:3000/konto.php?knr=' . $row['knr'] .'">
    
    <tr onclick="window.location=\'http://localhost:3000/konto.php?knr=' . $row['knr'] .'\'">
    <td>' . $row["bezeichnung"] . '</td>
    <td>' . $row["kontostand"] . '</td>
    </tr> </a>';
}

?>
    </tbody>
</table>


</body>
</html>