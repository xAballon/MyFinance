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
    
<h1>Transaktionen</h1>

<table>
    
  <thead>
    <tr>
      <th>Nummer</th>
      <th>Betrag</th>
      <th>Quellkonto</th>
      <th>Zielkonto</th>
      <th>Kommentar</th>
      <th>Zeit</th>

      <td></td>
    </tr>
  </thead>
  <tbody>
    <?php
$stmt = $pdo->prepare('SELECT 
t.betrag AS betrag,
t.tnr AS tnr,
t.kommentar AS kommentar,
t.quelle AS quell_kid,
t.ziel AS ziel_kid,
t.zeit AS zeit,
t.uid AS uid,
k1.bezeichnung AS ziel,
k1.knr AS ziel_knr,
k2.bezeichnung AS quelle,
k2.knr AS quell_knr
FROM 
transaktionen t
JOIN 
konto k1 ON t.ziel = k1.kid
JOIN 
konto k2 ON t.quelle = k2.kid
WHERE 
t.uid = :uid;');
$stmt->execute([':uid' => $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();$stmt->execute([':uid' => $_SESSION['user_id']]);
$konten = $stmt->fetchAll();

//nach aktuellstem sortieren
usort($transactions, function ($a, $b) {
    return $b['tnr'] <=> $a['tnr']; // Aufsteigend sortieren
});

foreach($transactions as $row){
    /* DEBUG VARDUMP
var_dump($row);
echo "<br><br>";
*/
echo "
<tr>
<td>" . $row['tnr'] ."</td>
<td>" . $row['betrag'] . "</td>
<td>" . $row['ziel'] . "</td>
<td>" . $row['quelle'] . "</td>
<td>" . $row['kommentar'] . "</td>
<td>" . $row['zeit'] . "</td>
<tr>
";
}
echo "</tbody></table>";


?>
    </tbody>
</table>


</body>
</html>