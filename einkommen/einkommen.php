<?php

include('../header.php');
require_once('../misc/dbConnection.php');

if (isset($_POST['laden'])) {
    $regelId = $_POST['regel_id'];
    // Abfrage der Regel und der zugehörigen Verteilungswerte
    $stmt = $pdo->prepare('
    SELECT ev.konto_id, ev.typ, ev.wert, er.betrag
    FROM einkommensverteilung ev
    JOIN einkommensregeln er ON ev.regel_id = er.id
    WHERE er.id = :regel_id');
    $stmt->execute([':regel_id' => $regelId]);
    $regelDetails = $stmt->fetchAll();
    $einkommen = $regelDetails[0]['betrag'];
    //var_dump($regelDetails);
    echo $einkommen;
} else if (isset($_POST['submit'])) {
    if (!empty($_POST['einkommen']) && !empty($_POST['betrag']) && !empty($_POST['prozent'])) {


        $betragsArray = $_POST['betrag'];
        $kidArray = $_POST['kid'];
        $kommentar = $_POST['kommentar'] ?? '';
        $quelle = 000;
        $uid = $_SESSION['user_id'];



        /*$data[] = $_POST['betrag'];

        foreach ($data as $betrag => $wert) {
            if ($wert == 0) {
                unset($data[$betrag]);
            }
        }
        var_dump($data);*/

        //tnr ermitteln
        $stmt = $pdo->prepare("SELECT MAX(tnr) FROM transaktionen WHERE uid = :uid");
        $stmt->execute([':uid' => $uid]);
        $max = $stmt->fetchColumn();
        $tnr = $max ? $max + 1 : 1;

        $quelle = $_POST['ekid'];

        $stmt = $pdo->prepare('INSERT INTO transaktionen (betrag, tnr, kommentar, quelle, ziel, uid)
                           VALUES (:betrag, :tnr, :kommentar, :quelle, :ziel, :uid)');

        foreach ($kidArray as $i => $zielKonto) {
            $betrag = floatval($betragsArray[$i]);

            // Nur hinzufügen, wenn Betrag > 0
            if ($betrag > 0) {
                $stmt->execute([
                    ':betrag' => $betrag,
                    ':tnr' => $tnr,
                    ':kommentar' => $kommentar,
                    ':quelle' => $quelle,
                    ':ziel' => $zielKonto,
                    ':uid' => $uid
                ]);

                //Kontostand aktualisieren
                $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :ziel")
                    ->execute([':betrag' => $betrag, ':ziel' => $zielKonto]);

                $tnr++;
            }

            $knr = $_POST['knr'];
            // Quelle wird abhängig vom Typ behandelt
            if ($knr < 100) {
                // Eingang = z. B. Gehaltseingang → auch das Quellkonto bekommt Geld
                $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :quelle")
                    ->execute([':betrag' => $betrag, ':quelle' => $quelle]);
            } else {
                // Sonst: Abzug vom Quellkonto
                $pdo->prepare("UPDATE konto SET kontostand = kontostand + :betrag WHERE kid = :quelle")
                    ->execute([':betrag' => $betrag, ':quelle' => $quelle]);
            }
        }
        header("LOCATION: ../dashboard.php");
    } else {
        die("Fehler!!! :(");
    }
}


?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eingang</title>
</head>
<link rel="stylesheet" href="../style.css">

<body>
    <form method="POST">
        <h1>Einkommen</h1>
        <br>
        <?php /*
        $stmt = $pdo->prepare('SELECT id, bezeichnung, betrag FROM einkommensregeln WHERE uid = :uid');
        $stmt->execute([':uid' => $_SESSION['user_id']]);
        $regeln = $stmt->fetchAll();

        if (isset($_POST['regel_id'])) {
            echo '<input type="hidden" name="regel_id" value="' . htmlspecialchars($_POST['regel_id']) . '">';
        }
        echo '<label for="regel">Wählen Sie eine Regel:</label>';
        echo '<select id="regel" name="regel_id"';
        echo '<option value="">---</option>';
        foreach ($regeln as $regel) {
            echo '<option value="' . $regel['id'] . '" ' . $selected . '>' . $regel['bezeichnung'] . ' (' . $regel['betrag'] . '€)</option>';
        }
        echo '</select>';
        <input type="submit" value="laden" name="laden">
        */ ?>
        <br><br>
    </form>

    <form method="POST" id="form">



        <label for="einkommen">Einkommen: </label>
        <input type="number" name="einkommen" id="einkommen" value="<?php echo $einkommen; ?>" min=0.01 max=999999999.99 step="0.01" required>
        <p>Verbleibend: <span id="verbleibend">0.00</span> € / <span id="verbleibendP">0.00</span>%</p>
        <table>

            <thead>
                <tr>
                    <th>Konto</th>
                    <th>Kontostand</th>
                    <th></th>
                    <th></th>
                    <td></td>
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

                        // Falls eine Regel geladen wurde, die Beträge und Prozente aus der Regel pre-füllen
                        $betrag = 0;
                        $prozent = 0;

                        if (isset($regelDetails)) {

                            foreach ($regelDetails as $regel) {
                                if ($regel['konto_id'] == $row['kid']) {
                                    if ($regel['typ'] == 'prozent') {
                                        $prozent = $regel['wert'];
                                    } else {
                                        $betrag = $regel['wert'];
                                    }
                                }
                            }
                        }

                        echo '<a href="http://localhost:3000/konto.php?knr=' . $row['knr'] . '">
                            
                            <tr class="zeile">
                            <td>' . $row["bezeichnung"] . '</td>
                            <td>' . $row["kontostand"] . '</td>
                            <td><input type="number" name="betrag[]" class="betrag" min="0" max="999999999.99" step="0.01" required value=' . $betrag . '>€</td>
                            <td><input type="number" name="prozent[]" class="prozent" min="0" max="100" step="0.01" required value=' . $prozent . '>% </td>
                            <td> <input type="hidden" name="knr[]" value="' . $row["knr"] . '"> </td>
                            <td> <input type="hidden" name="kid[]" value="' . $row["kid"] . '"> </td>
                            </tr> </a>';
                    }
                }
                ?>
            </tbody>
        </table>
        <br>
        <label for="kommentar">Kommentar: </label>
        <input type="text" name="kommentar">
        <input type="submit" name="submit" value="fertig">

    </form>



    <script>
        const einkommenInput = document.getElementById('einkommen');
        const verbleibendEl = document.getElementById('verbleibend');
        const verbleibend2El = document.getElementById('verbleibendP');
        const zeilen = document.querySelectorAll('.zeile');

        function update(quelle = null) {
            const einkommen = parseFloat(einkommenInput.value) || 0;
            let gesamt = 0;

            zeilen.forEach(zeile => {
                const betragFeld = zeile.querySelector('.betrag');
                const prozentFeld = zeile.querySelector('.prozent');

                let betrag = parseFloat(betragFeld.value) || 0;
                let prozent = parseFloat(prozentFeld.value) || 0;

                // Nur aktualisieren, wenn dieses Feld der Auslöser war
                if (quelle === betragFeld) {
                    prozentFeld.value = einkommen ? ((betrag / einkommen) * 100).toFixed(2) : '';
                } else if (quelle === prozentFeld) {
                    betragFeld.value = ((prozent / 100) * einkommen).toFixed(2);
                }

                zeile.classList.remove('error');
                gesamt += parseFloat(betragFeld.value) || 0;
            });

            const verbleibend = einkommen - gesamt;
            verbleibendEl.textContent = (einkommen - gesamt).toFixed(2);
            verbleibend2El.textContent = ((1 - gesamt / einkommen) * 100).toFixed(2);

            verbleibendEl.classList.toggle('verbleibend-null', verbleibend === 0);
            verbleibend2El.classList.toggle('verbleibend-null', verbleibend === 0);
            verbleibendEl.classList.toggle('error-txt', verbleibend < 0);
            verbleibend2El.classList.toggle('error-txt', verbleibend < 0);

            // Fehler markieren, wenn zu viel verteilt
            if (verbleibend < 0 && quelle) {
                const aktivZeile = quelle.closest('.zeile');
                aktivZeile.classList.add('error');
            }
        }

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const debouncedUpdate = debounce((feld) => update(feld), 1000);

        einkommenInput.addEventListener('input', () => update());

        setTimeout((betragFeld) => update(betragFeld), 200);
        setTimeout((prozentFeld) => update(prozentFeld), 200);

        zeilen.forEach(zeile => {
            const betragFeld = zeile.querySelector('.betrag');
            const prozentFeld = zeile.querySelector('.prozent');

            betragFeld.addEventListener('input', () => debouncedUpdate(betragFeld));
            prozentFeld.addEventListener('input', () => debouncedUpdate(prozentFeld));

            betragFeld.addEventListener('blur', () => update(betragFeld));
            prozentFeld.addEventListener('blur', () => update(prozentFeld));
        });

        update();

        document.getElementById('regel').addEventListener('change', function() {
            this.form.submit();
        });

/*
        const formular = document.getElementById('form');
        const verbleibend = document.getElementById('verbleibend');

        formular.addEventListener('submit', function(event) {
            if (isNaN(wert) ||parseFloat(verbleibend.value) !== 0) {
                event.preventDefault(); // Verhindert das Absenden
                alert('Differenz muss 0 sein, um das Formular abzuschicken!');
            }
        });*/
    </script>

</body>

<?php include('../footer.php'); ?>
</html>
