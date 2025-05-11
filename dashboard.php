<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MyFinance Dashboard</title>
  <link rel="stylesheet" href="../style.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <header>
        <?php include('header.php'); ?>
    </header>

    <h1>Willkommen, Peter!</h1>

  <div class="dashboard-container">

    <!-- Budget Statistik -->
    <div class="dashboard-section">
      <h3>Budget Statistik</h3>
      <div class="chart-wrapper">
        <canvas id="budgetChart"></canvas>
      </div>
    </div>

    <!-- Transaktionsbuttons -->
    <div class="dashboard-section">
      <h3>Transaktionen</h3>
      <div class="button-grid">
        <a href="../transaction/transactions.php?type=eingang" class="myf-button">➕ Einnahmen</a>
        <a href="../transaction/transactions.php?type=ausgang" class="myf-button">➖ Ausgaben</a>
        <a href="../transaction/transactions.php?type=transfer" class="myf-button">🔄 Umbuchen</a>
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
    const ctx = document.getElementById('budgetChart').getContext('2d');

    // Beispiel-Daten – diese sollten dynamisch mit PHP generiert werden
    const einnahmen = 6000;
    const ausgaben = 1500;

    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Einnahmen', 'Ausgaben'],
        datasets: [{
          data: [einnahmen, ausgaben],
          backgroundColor: ['#24D1C2', '#FF4D4D'],
          borderWidth: 1
        }]
      },
      options: {
        cutout: '70%',
        plugins: {
          legend: {
            labels: {
              color: '#FFFFFF'
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
