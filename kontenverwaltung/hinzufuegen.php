<?php
include('../header.php');
include('../misc/check_login.php');
include('../misc/dbConnection.php');

$fehler = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bezeichnung = trim($_POST['bezeichnung'] ?? '');
    $kontostand = trim($_POST['kontostand'] ?? '');
    $knr = trim($_POST['knr'] ?? '');

    // Einfache Validierung
    if ($bezeichnung === '' || !is_numeric($knr)) {
        $fehler = "Bitte gültige Werte eingeben.";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) as Anzahl FROM konto WHERE knr = ? AND uid = ?");
        $stmt->execute([$knr, $_SESSION['user_id']]);
        $anz = $stmt->fetchColumn();

        if ($anz > 0) {
            $fehler = "Konto existiert bereits.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO konto (bezeichnung, kontostand, knr, uid) VALUES (?, ?, ?, ?)");
                $stmt->execute([$bezeichnung, 0, $knr, $_SESSION['user_id']]);
                header("Location: kontenverwaltung.php");
                exit;
            } catch (Exception $e) {
                $fehler = "Fehler beim Hinzufügen: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Neues Konto hinzufügen</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="konto-form-container">
        <h2>Neues Konto hinzufügen</h2>

        <?php if ($fehler): ?>
            <p class="error-message"><?php echo htmlspecialchars($fehler); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="bezeichnung">Bezeichnung:</label>
            <input type="text" name="bezeichnung" id="bezeichnung" required>

            <label for="knr">Kontonummer (knr):</label>
            <input type="number" name="knr" id="knr" min="100" max="899" required>

            <button type="submit">Konto hinzufügen</button>
        </form>

        <a href="kontenverwaltung.php" class="back-link">← Zurück zur Übersicht</a>
    </div>
</body>

<?php include('../footer.php'); ?>
</html>
