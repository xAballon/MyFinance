<?php
session_start();

// Prüfen, ob der Benutzer eingeloggt ist
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyFinance - Header</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <a href="../index.php" class="logo"><img src="../images/MyFinance_Logo.png" alt="Logo_MyFinance"></a>
            <span class="logo-text">MyFinance</span>
        </div>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="../about.php">Über uns</a>
            <a href="../login/login.php">Registrieren</a>
        </div>
        
        <!-- Profil-Icon mit Dropdown-Menü -->
        <div class="profile-dropdown">
            <div class="profile-icon" onclick="toggleDropdown()">
            <?php if ($isLoggedIn): ?>
                    <!-- Icon für eingeloggte Benutzer -->
                    <span class="material-icons-outlined">person</span>
                <?php else: ?>
                    <!-- Standard-Icon für nicht eingeloggte Benutzer -->
                    <span class="material-icons-outlined">login</span>

                <?php endif; ?>
            </div>
            </div>
            <div class="dropdown-content" id="dropdownMenu">
                <?php if ($isLoggedIn): ?>
                    <!-- Wenn eingeloggt -->
                    <a href="../profile.php">Profil</a>
                    <a href="../login/logout.php">Ausloggen</a>
                <?php else: ?>
                    <!-- Wenn nicht eingeloggt -->
                    <a href="../login/login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="spacer"></div>

    <script>
        // Funktion zum Umschalten des Dropdowns
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('show');
        }

        // Schließen des Dropdowns, wenn man außerhalb klickt
        window.onclick = function(event) {
            if (!event.target.matches('.profile-icon')) {
                const dropdownMenu = document.getElementById('dropdownMenu');
                if (dropdownMenu.classList.contains('show')) {
                    dropdownMenu.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>
