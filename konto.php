<?php
include('misc/check_login.php');
include('misc/dbConnection.php');

if(isset($_GET['knr']) && !empty($_GET['knr'])){

$knr = $_GET['knr'];

$stmt = $pdo->prepare('SELECT 
    t.betrag AS betrag,
    t.tnr AS tnr,
    t.kommentar AS kommentar,
    t.quelle AS quell_kid,
    t.ziel AS ziel_kid,
    t.zeit AS zeit,
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
    (k1.knr = :knr OR k2.knr = :knr)
    AND k1.uid = :uid
    AND k2.uid = :uid;');
$stmt->execute([':knr' => $knr, ':uid' => $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();

/*$stmt = $pdo->prepare('SELECT * FROM transaktionen JOIN konto ON transaktionen.quelle = konto.kid WHERE konto.knr = :knr AND konto.uid = :uid');
$stmt->execute([':knr' => $knr, ':uid' => $_SESSION['user_id']]);
$transactions = array_merge($transactions, $stmt->fetchAll());
*/
$stmt = $pdo->prepare('SELECT bezeichnung, knr FROM Konto WHERE knr=:knr AND uid=:uid');
$stmt->execute([':knr' => $knr, ':uid' => $_SESSION['user_id']]);
$konto = $stmt->fetch();
echo "<h1>Konto Nr. " . $konto['knr'] . " - " . $konto['bezeichnung'] . "</h1>";

echo "<table>
  <thead>
    <tr>
      <th> +/-</th>
      <th>Betrag</th>
      <th>Von Konto</th>
      <th>Auf Konto</th>
      <th>Kommentar</th>
      <th>Transaktion</th>
      <th>Zeit</th>
    </tr>
  </thead>
  <tbody>
";

//nach aktuellstem sortieren
usort($transactions, function($a, $b) {
    return strtotime($b['zeit']) - strtotime($a['zeit']);
});

foreach($transactions as $row){
    /* DEBUG VARDUMP
var_dump($row);
echo "<br><br>";
*/
echo "
<tr>
<td>"; if($knr == $row['ziel_knr']){echo "+";}else{echo "-";}; echo "</td>
<td>" . $row['betrag'] ."</td>
<td>" . $row['quelle'] . "</td>
<td>" . $row['ziel'] . "</td>
<td>" . $row['kommentar'] . "</td>
<td>" . $row['tnr'] . "</td>
<td>" . $row['zeit'] . "</td>
<tr>
";
}
echo "</tbody></table>";

}else{
    die('Kein Konto eingegeben');
}

?>