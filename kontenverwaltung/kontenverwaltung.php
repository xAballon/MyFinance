<?php
include('../header.php');
include('../misc/check_login.php');
include('../misc/dbConnection.php');
?>
<div>
<div >
    <?php
    $stmt = $pdo->prepare('SELECT knr, bezeichnung, kontostand FROM Konto WHERE uid = :uid');
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $konten = $stmt->fetchAll();

    foreach ($konten as $row):
        $betrag = (float)$row["kontostand"];
        $farbe = $betrag > 0 ? "positive" : ($betrag < 0 ? "negative" : "neutral");
    ?>
            <div ><?= htmlspecialchars($row["bezeichnung"]) ?></div>
            <div  ><?= number_format($betrag, 2, ',', '.') ?> €</div>


        <?php endforeach; ?>
</div>
</div>
<?php include('../footer.php'); ?>