<?php
include('../header.php');
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
<html>

<head>
    <meta charset="UTF-8">
    <title>Konto hinzufügen</title>
</head>

<body>
    <h2>Neues Konto hinzufügen</h2>

    <?php if ($fehler): ?>
        <p style="color:red;"><?php echo htmlspecialchars($fehler); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Bezeichnung:<br>
            <input type="text" name="bezeichnung" required>
        </label><br><br>

        <label>Kontonummer (knr):<br>
            <input type="number" name="knr" min="100" max="899" required>
        </label><br><br>

        <button type="submit">Konto hinzufügen</button>
    </form>

    <p><a href="kontenverwaltung.php">Zurück zur Übersicht</a></p>
</body>
<?php include('../footer.php');
?>

</html>