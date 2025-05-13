<?php
require_once('../misc/dbConnection.php');
include('../misc/check_login.php');


if (isset($_POST['laden'])) {
    $regelId = $_POST['regel_id'];
    $stmt = $pdo->prepare('
        SELECT ev.konto_id, ev.typ, ev.wert, er.betrag
        FROM einkommensverteilung ev
        JOIN einkommensregeln er ON ev.id = er.id
        WHERE er.id = :regel_id');
    $stmt->execute([':regel_id' => $regelId]);
    $regelDetails = $stmt->fetchAll();
    $einkommen = $regelDetails[0]['betrag'];
} elseif (isset($_POST['submit'])) {
    if (!empty($_POST['einkommen']) && !empty($_POST['betrag']) && !empty($_POST['prozent'])) {
        $betragsArray = $_POST['betrag'];
        $kidArray = $_POST['kid'];
        $kommentar = $_POST['kommentar'] ?? '';
        $uid = $_SESSION['user_id'];

        $stmt = $pdo->prepare("SELECT MAX(tnr) FROM transaktionen WHERE uid = :uid");
        $stmt->execute([':uid' => $uid]);
        $tnr = $stmt->fetchColumn() + 1 ?: 1;

        $quelle = $_POST['ekid'];

        $stmt = $pdo->prepare('INSERT INTO transaktionen (betrag, tnr, kommentar, quelle, ziel, uid)
                               VALUES (:betrag, :tnr, :kommentar, :quelle, :ziel, :uid)');

        foreach ($kidArray as $i => $zielKonto) {
            $betrag = floatval($betragsArray[$i]);
            if ($betrag > 0) {
                $stmt->execute([
                    ':betrag' => $betrag,
                    ':tnr' => $tnr,
                    ':kommentar' => $kommentar,
                    ':quelle' => $quelle,
                    ':ziel' => $zielKonto,
                    ':uid' => $uid
                ]);

                $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :ziel")
                    ->execute([':betrag' => $betrag, ':ziel' => $zielKonto]);

                $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :quelle")
                    ->execute([':betrag' => $betrag, ':quelle' => $quelle]);

                $tnr++;
            }
        }

        header("Location: ../dashboard.php");
        exit;
    } else {
        die("Fehler: Alle Felder müssen ausgefüllt sein!");
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>Einkommensverteilung</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">


    <style>
        .form-container {
    max-width: 800px;
    margin: auto;
    padding: 2rem;
    background: #1763B2;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: white;
    text-shadow: 2px 2px 3px #11afce
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: .3rem;
}

input[type="number"],
input[type="text"] {

    padding: .6rem;
    border-radius: 5px;
    border: 1px solid #ccc;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    background-color: #1BA3BD;
    border-radius: 8px;
}

th,
td {
    padding: .8rem;
    text-align: center;
}

.betrag,
.prozent {
    width: 90px;
}

.verbleibend-box {
    background-color: #11afce;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
}

.verbleibend-null {
    color: rgb(17, 255, 0);
    font-weight: bold;
}

.error-txt {
    color: red;
    font-weight: bold;
}

.zeile.error {
    background-color: #ffe6e6;
}

input[type="submit"] {
    background-color: #1763B2;
    color: white;
    border-radius: 5px;
    cursor: pointer;

}

input[type="submit"]:hover {
    background-color: #1BA3BD;
}
    </style>
</head>

<body>
    <header>
        <?php include('../header.php'); ?>
    </header>

    <div class="form-container">
        <form method="POST" id="form">
            <h1>Einkommensverteilung</h1>

            <div class="form-group">
                <label for="einkommen">Einkommen (€):</label>
                <input type="number" placeholder="0" name="einkommen" id="einkommen" value="<?= $einkommen ?? NULL ?>" min="0.01" max="999999999.99" step="0.01" required>
            </div>

            <div class="verbleibend-box">
                <span>Verbleibend: <span id="verbleibend">0.00</span> €</span>
                <span><span id="verbleibendP">0.00</span>%</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Konto</th>
                        <th>Kontostand</th>
                        <th>Betrag (€)</th>
                        <th>Prozent (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare('SELECT kid, knr, bezeichnung, kontostand FROM Konto WHERE uid = :uid AND knr BETWEEN 0 AND 899');
                    $stmt->execute([':uid' => $_SESSION['user_id']]);
                    $konten = $stmt->fetchAll();

                    foreach ($konten as $row) {
                        if ($row['knr'] < 100) {
                            echo '<input type="hidden" name="ekid" value="' . $row["kid"] . '">';
                        } else {
                            $betrag  = NULL;
                            $prozent = NULL;
                            if (isset($regelDetails)) {
                                foreach ($regelDetails as $regel) {
                                    if ($regel['konto_id'] == $row['kid']) {
                                        $regel['typ'] === 'prozent' ? $prozent = $regel['wert'] : $betrag = $regel['wert'];
                                    }
                                }
                            }

                            echo '<tr class="zeile">
                            <td>' . htmlspecialchars($row["bezeichnung"]) . '</td>
                            <td>' . number_format($row["kontostand"], 2, ',', '.') . ' €</td>
                            <td><input type="number" class="betrag" name="betrag[]" min="0" step="0.01" placeholder="0" value="' . $betrag . '" required></td>
                            <td><input type="number" class="prozent" name="prozent[]" min="0" max="100" step="0.01" value="' . $prozent . '" required></td>
                            <input type="hidden" name="knr[]" value="' . $row["knr"] . '">
                            <input type="hidden" name="kid[]" value="' . $row["kid"] . '">
                        </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>

            <div class="form-group">
                <label for="kommentar">Kommentar</label>
                <textarea type="textfield" name="kommentar" id="kommentar" style="    width: 100%;
    padding: 0.7rem;
    border: none;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    font-size: 1rem; max-width: 100%; min-width: 100%"></textarea>
            </div>

            <input type="submit" name="submit" value="Fertig" class="myf-button">
        </form>
    </div>

    <script>
        // JavaScript für automatische Berechnung (wie von dir geliefert, aber integriert)
        const einkommenInput = document.getElementById('einkommen');
        const verbleibendEl = document.getElementById('verbleibend');
        const verbleibendP = document.getElementById('verbleibendP');
        const zeilen = document.querySelectorAll('.zeile');

        function update(feld = null) {
            const einkommen = parseFloat(einkommenInput.value) || 0;
            let gesamt = 0;

            zeilen.forEach(zeile => {
                const betragFeld = zeile.querySelector('.betrag');
                const prozentFeld = zeile.querySelector('.prozent');

                let betrag = parseFloat(betragFeld.value) || 0;
                let prozent = parseFloat(prozentFeld.value) || 0;

                if (feld === betragFeld) {
                    prozentFeld.value = einkommen ? ((betrag / einkommen) * 100).toFixed(2) : '';
                } else if (feld === prozentFeld) {
                    betragFeld.value = ((prozent / 100) * einkommen).toFixed(2);
                }

                gesamt += parseFloat(betragFeld.value) || 0;
                zeile.classList.remove('error');
            });

            const verbleibend = einkommen - gesamt;
            verbleibendEl.textContent = verbleibend.toFixed(2);
            verbleibendP.textContent = ((1 - gesamt / einkommen) * 100).toFixed(2);

            verbleibendEl.classList.toggle('error-txt', verbleibend < 0);
            verbleibendP.classList.toggle('error-txt', verbleibend < 0);
            verbleibendEl.classList.toggle('verbleibend-null', verbleibend === 0);
            verbleibendP.classList.toggle('verbleibend-null', verbleibend === 0);
        }

        einkommenInput.addEventListener('input', () => update());
        zeilen.forEach(zeile => {
            const betragFeld = zeile.querySelector('.betrag');
            const prozentFeld = zeile.querySelector('.prozent');
            betragFeld.addEventListener('input', () => update(betragFeld));
            prozentFeld.addEventListener('input', () => update(prozentFeld));
        });
        update();
    </script>

    <?php include('../footer.php'); ?>
</body>

</html>