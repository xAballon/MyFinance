<?php

include('../misc/check_login.php');
require_once('../misc/dbConnection.php');
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
    <h1>Einkommen</h1>

    <form action="" method="POST">

        <label for="einkommen">Einkommen: </label>
        <input type="number" name="einkommen" id="einkommen" value="2250" min=0.01 max=999999999.99 step="0.01" required>
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
                $stmt = $pdo->prepare('SELECT knr, bezeichnung, kontostand FROM Konto WHERE uid = :uid AND knr BETWEEN 100 AND 899');
                $stmt->execute([':uid' => $_SESSION['user_id']]);
                $konten = $stmt->fetchAll();

                foreach ($konten as $row) {
                    echo '<a href="http://localhost:3000/konto.php?knr=' . $row['knr'] . '">
      
      <tr class="zeile">
      <td>' . $row["bezeichnung"] . '</td>
      <td>' . $row["kontostand"] . '</td>
      <td>' . '<input type="number" name="betrag" class="betrag" min="0" max="999999999.99" step="0.01" reqired value="0">' . "€" . '</td>
      <td>' . '<input type="number" name="prozent" class="prozent" min="0" max="100" step="0.01" reqired value="0">' . "%" . '</td>
      </tr> </a>';
                }
                ?>
            </tbody>
        </table>

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

  zeilen.forEach(zeile => {
    const betragFeld = zeile.querySelector('.betrag');
    const prozentFeld = zeile.querySelector('.prozent');

    betragFeld.addEventListener('input', () => debouncedUpdate(betragFeld));
    prozentFeld.addEventListener('input', () => debouncedUpdate(prozentFeld));

    betragFeld.addEventListener('blur', () => update(betragFeld));
    prozentFeld.addEventListener('blur', () => update(prozentFeld));
  });

  update();
</script>


    <!--<script>
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const delayedUpdate = debounce(update, 1000);
        const einkommenInput = document.getElementById('einkommen');
        const verbleibendEl = document.getElementById('verbleibend');
        const verbleibend2El = document.getElementById('verbleibendP');
        const zeilen = document.querySelectorAll('.zeile');

        function update() {
            const einkommen = parseFloat(einkommenInput.value) || 0;
            let gesamt = 0;

            zeilen.forEach(zeile => {
                const betragFeld = zeile.querySelector('.betrag');
                const prozentFeld = zeile.querySelector('.prozent');

                const betrag = parseFloat(betragFeld.value) || 0;
                const prozent = parseFloat(prozentFeld.value) || 0;

                if (document.activeElement === betragFeld) {
                    if (betragFeld.value === '') {
                        prozentFeld.value = '';
                    } else {
                        prozentFeld.value = ((betrag / einkommen) * 100).toFixed(2);
                    }
                } else if (document.activeElement === prozentFeld) {
                    if (prozentFeld.value === '') {
                        betragFeld.value = '';
                    } else {
                        betragFeld.value = ((prozent / 100) * einkommen).toFixed(2);
                    }
                }

                zeile.classList.remove('error');
                gesamt += parseFloat(betragFeld.value) || 0;
            });

            verbleibendEl.textContent = (einkommen - gesamt).toFixed(2);
            verbleibend2El.textContent = ((1 - gesamt / einkommen) * 100).toFixed(2);

            if ((einkommen - gesamt).toFixed(2) == 0) {
                verbleibendEl.classList.add('verbleibend-null');
                verbleibend2El.classList.add('verbleibend-null');
            } else {
                verbleibendEl.classList.remove('verbleibend-null');
                verbleibend2El.classList.remove('verbleibend-null');
            }

            // Wenn zu viel verteilt wurde
            if (gesamt > einkommen) {
                const aktiv = document.activeElement.closest('.zeile');
                aktiv.classList.add('error');
                verbleibendEl.classList.add('error-txt');
                verbleibend2El.classList.add('error-txt');
            } else {
                verbleibendEl.classList.remove('error-txt');
                verbleibend2El.classList.remove('error-txt');
            }
        }

        einkommenInput.addEventListener('input', delayedUpdate);
        einkommenInput.addEventListener('blur', update);

        zeilen.forEach(zeile => {
            betragFeld.addEventListener('input', debouncedUpdate);
            prozentFeld.addEventListener('input', debouncedUpdate);

            betragFeld.addEventListener('blur', update);
            prozentFeld.addEventListener('blur', update);
        });




        update(); // initialer Aufruf
    </script>-->


</body>


</html>