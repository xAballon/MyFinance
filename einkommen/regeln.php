<?php

include('../header.php');
require_once('../misc/dbConnection.php');

//mehr ai-gen
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['speichern'])) {
    $bezeichnung = trim(htmlspecialchars($_POST['name']));
    $betrag = floatval($_POST['betrag']);
    $uid = $_SESSION['user_id'];

    $pdo->beginTransaction();

    try {
        // Hauptregel einfügen
        $stmt = $pdo->prepare("INSERT INTO einkommensregeln (uid, bezeichnung, betrag,  aktiv) VALUES (?, ?, ?)");
        $stmt->execute([$uid, $bezeichnung, $betrag]);
        $regelId = $pdo->lastInsertId();

        // Regel-Details einfügen
        foreach ($_POST['konto_id'] as $i => $kontoId) {
            $typ = $_POST['typ'][$i];
            $wert = floatval($_POST['wert'][$i]);

            if ($wert > 0) {
                $stmt = $pdo->prepare("INSERT INTO einkommensverteilung (regel_id, konto_id, typ, wert) VALUES (?, ?, ?, ?)");
                $stmt->execute([$regelId, $kontoId, $typ, $wert]);
            }
        }

        $pdo->commit();
        header("Location: ../dashboard.php");
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Fehler: " . $e->getMessage();
    }
}



?>

<form method="POST" action="regeln.php" id="form">
    <input type="number" name="betrag" id="gesamtbetrag" step="0.01" placeholder="Gesamtbetrag" required>
    <input type="text" name="name" placeholder="Regel-Name" max="128" required>
    <br><p id="restbetrag" style="font-weight: bold;">Noch zu verteilen: 0.00 €</p>

    <table>
        <thead>
            <tr>
                <th>Konto</th>
                <th>Typ</th>
                <th>Wert</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->prepare('SELECT kid, knr, bezeichnung FROM konto WHERE uid = :uid AND knr BETWEEN 100 AND 899');
            $stmt->execute([':uid' => $_SESSION['user_id']]);
            foreach ($stmt->fetchAll() as $konto) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($konto['bezeichnung']) . '</td>';
                echo '<td>
                        <select name="typ[]" class="typ">
                        <option value="fix">€</option>
                            <option value="prozent">%</option>
                        </select>
                      </td>';
                echo '<td>
                        <input type="hidden" name="konto_id[]" value="' . $konto['kid'] . '">
                        <input type="number" name="wert[]" class="wert" step="0.01" min="0" value="0">
                      </td>';
                echo '</tr>';
            }
           ?>
        </tbody>
    </table>

    <button type="submit" name="speichern">Regel speichern</button>
</form>

        <!-- Check ob summe stimmt -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const gesamtbetrag = parseFloat(document.getElementById('gesamtbetrag').value);
    const werte = document.querySelectorAll('.wert');
    const typen = document.querySelectorAll('.typ');

    let summe = 0;

    for (let i = 0; i < werte.length; i++) {
        const wert = parseFloat(werte[i].value);
        const typ = typen[i].value;

        if (!isNaN(wert) && wert > 0) {
            if (typ === 'prozent') {
                summe += (wert / 100) * gesamtbetrag;
            } else {
                summe += wert;
            }
        }
    }

    // Erlaubte Abweichung wegen Rundungsfehlern z.B. 0.01 €
    const differenz = Math.abs(summe - gesamtbetrag);

    if (differenz > 0.01) {
        e.preventDefault(); // Verhindert das Absenden
        alert(`Die Summe der Unterverteilungen (${summe.toFixed(2)} €) entspricht nicht dem Gesamtbetrag (${gesamtbetrag.toFixed(2)} €).`);
    }
});


function berechneRestbetrag() {
    const gesamtbetrag = parseFloat(document.getElementById('gesamtbetrag').value) || 0;
    const werte = document.querySelectorAll('.wert');
    const typen = document.querySelectorAll('.typ');

    let summe = 0;

    for (let i = 0; i < werte.length; i++) {
        const wert = parseFloat(werte[i].value);
        const typ = typen[i].value;

        if (!isNaN(wert) && wert > 0) {
            if (typ === 'prozent') {
                summe += (wert / 100) * gesamtbetrag;
            } else {
                summe += wert;
            }
        }
    }

    const rest = gesamtbetrag - summe;
    const anzeige = document.getElementById('restbetrag');
    anzeige.textContent = `Noch zu verteilen: ${rest.toFixed(2)} €`;

    // Optional: Farbe bei negativem Restbetrag
    if (rest < 0) {
        anzeige.style.color = "red";
    } else if (rest === 0) {
        anzeige.style.color = "green";
    } else {
        anzeige.style.color = "black";
    }
}

// Live-Update bei jeder Änderung
document.getElementById('gesamtbetrag').addEventListener('input', berechneRestbetrag);
document.querySelectorAll('.wert, .typ').forEach(el => {
    el.addEventListener('input', berechneRestbetrag);
    el.addEventListener('change', berechneRestbetrag);
});


</script>