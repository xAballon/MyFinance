<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyFinance | Startseite</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <?php 
            include('header.php');
        ?>
    </header>
    
    <main>
        <!-- Hero -->
        <section class="hero">
            <h1>MyFinance</h1>
            <div class="hero-images">
                <img src="images/Beispielbild.jpg" alt="Bild 1">
                <img src="images/Beispielbild.jpg" alt="Bild 2">
            </div>
        </section>

        <!-- Features -->
        <section class="features">
            <div class="feature-box">
                <img src="images/Beispielbild.jpg" alt="Feature 1">
                <h3>Smarte Übersicht</h3>
                <p>Behalte deine Ausgaben und Einnahmen jederzeit im Blick.</p>
            </div>
            <div class="feature-box">
                <img src="images/Beispielbild.jpg" alt="Feature 2">
                <h3>Intelligente Analysen</h3>
                <p>Visualisiere deine Finanzdaten mit übersichtlichen Grafiken.</p>
            </div>
        </section>

        <!-- Vorteile -->
        <section class="benefits">
            <ul>
                <li>Einfache Verwaltung aller Konten</li>
                <li>Kostenkontrolle mit nur wenigen Klicks</li>
                <li>Sichere Daten dank moderner Verschlüsselung</li>
                <li>Mobil & am Desktop nutzbar</li>
                <li>Kostenlos starten – jederzeit kündbar</li>
            </ul>
        </section>
    </main>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>
</body>
</html>