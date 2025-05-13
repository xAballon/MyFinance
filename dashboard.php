<?php 
include('misc/check_login.php');
require_once('misc/dbConnection.php');
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MyFinance | Dashboard</title>
  <link rel="stylesheet" href="../style.css" />
  <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
  <header>
    <?php include('header.php'); ?>
  </header>

  <!-- Willkommen + [Name] -->
  <?php
    $stmt = $pdo->prepare('SELECT vorname, nachname FROM user WHERE uid = :uid');
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
  ?>
  <?php if ($user): ?>
      <h2 class="myf-welcome">Guten Tag, <?= htmlspecialchars($user['vorname']) ?> <?= htmlspecialchars($user['nachname']) ?>!</h2>
  <?php endif; ?>

  <div class="dashboard-container">

    <!-- Budget Statistik -->
    <div class="dashboard-section">
      <h3>Budget Statistik</h3>
      <div class="chart-wrapper">
        <canvas id="balkendiagramm"></canvas>
      </div>
    </div>

    <!-- Transaktionsbuttons -->
    <div class="dashboard-section">
      <h3>Transaktionen</h3>
      <div class="button-grid">
        <a href="../transaction/transactions.php?type=eingang" class="myf-button">➕ Einnahmen</a>
        <a href="../transaction/transactions.php?type=ausgang" class="myf-button">➖ Ausgaben</a>
        <a href="../transaction/transactions.php?type=transfer" class="myf-button">🔄 Umbuchen</a>
        <a href="../einkommen/einkommen.php" class="myf-button">➕ Einkommen</a>
        <h3>Sonstiges</h3>
          <a href="../transaktionenliste.php" class="myf-button">Transaktionsliste</a>
          <a href="../kontenverwaltung/kontenverwaltung.php" class="myf-button">Kontenverwaltung</a>
      </div>
    </div>

    <!-- Kontenliste -->
    <div class="dashboard-section">
      <h3>Kontenübersicht</h3>
      <div class="kontenliste">
        <?php include('kontenliste.php'); ?>
      </div>
    </div>

  </div>

<script>

    <?php
      // Einnahmen (Kontonummer 000) holen
    $stmt = $pdo->prepare('SELECT knr, kontostand FROM Konto WHERE uid = :uid AND knr = "000"');
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $einnahmen = $stmt->fetchAll();

    foreach ($einnahmen as $row):
      $betrag_einnahmen = (float) $row['kontostand'];
    endforeach;

      // Ausgaben (Kontonummer 999) holen
    $stmt = $pdo->prepare('SELECT knr, kontostand FROM Konto WHERE uid = :uid AND knr = "999"');
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $ausgaben = $stmt->fetchAll();

    foreach ($ausgaben as $row):
      $betrag_ausgaben = (float) $row['kontostand'];
    endforeach;
    ?>

    const einnahmen = <?= $betrag_einnahmen; ?>;
    const ausgaben = <?= $betrag_ausgaben; ?>;

    const ctx = document.getElementById('balkendiagramm').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Einnahmen', 'Ausgaben'],
            datasets: [{
                label: 'Beträge in €',
                data: [einnahmen, ausgaben],
                backgroundColor: [
                    '#24D1C2', // Türkis für Einnahmen
                    '#FF4444'  // Rot für Ausgaben
                ],
                borderColor: '#ffff',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    color: '#ffff',
                    font: {
                        size: 20,
                        weight: 'bold'
                    }
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        },
                        color: '#ffff'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffff'
                    }
                }
            }
        }
    });
</script>

  <footer>
    <?php include('footer.php'); ?>
  </footer>

</body>

</html>