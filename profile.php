<?php
include('misc/check_login.php');
require_once('misc/dbConnection.php');

$stmt = $pdo->prepare('SELECT vorname, nachname, email FROM user WHERE uid = :id');
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>MyFinance | Profil</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">

</head>
<body>
    <header>
        <?php include('header.php'); ?>
    </header>
    <div class="profile-container">
        <h1>Profil bearbeiten</h1>

        <form id="updateForm" action="update_profile.php" method="POST" onsubmit="return confirmUpdate()">
            <label for="vorname">Vorname:</label>
            <input type="text" id="vorname" name="vorname" value="<?= htmlspecialchars($user['vorname']) ?>" required>

            <label for="nachname">Nachname:</label>
            <input type="text" id="nachname" name="nachname" value="<?= htmlspecialchars($user['nachname']) ?>" required>

            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>

            <input type="submit" value="Speichern">
        </form>

        <form id="deleteForm" action="delete_profile.php" method="POST" onsubmit="return confirmDelete()">
            <input type="submit" class="delete-btn" value="Profil löschen">
        </form>
    </div>

    <script>
        function confirmUpdate() {
            return confirm('Möchtest du deine Änderungen wirklich speichern?');
        }

        function confirmDelete() {
            return confirm('Bist du sicher, dass du dein Profil unwiderruflich löschen willst?');
        }
    </script>

    <footer>
        <?php include('footer.php'); ?>
    </footer>
</body>
</html>
