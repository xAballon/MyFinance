<?php
include('misc/check_login.php');
include('misc/dbConnection.php');
?>
  <div class="konto-container">
    <?php
    $stmt = $pdo->prepare('SELECT knr, bezeichnung, kontostand FROM Konto WHERE uid = :uid');
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $konten = $stmt->fetchAll();

    foreach ($konten as $row):
      $betrag = (float)$row["kontostand"];
      $farbe = $betrag > 0 ? "positive" : ($betrag < 0 ? "negative" : "neutral");
      ?>
      <a class="konto-card" href="konto.php?knr=<?= $row['knr'] ?>">
        <div class="konto-name"><?= htmlspecialchars($row["bezeichnung"]) ?></div>
        <div class="kontostand <?= $farbe ?>"><?= number_format($betrag, 2, ',', '.') ?> €</div>
      </a>
    <?php endforeach; ?>
  </div>
</div>
