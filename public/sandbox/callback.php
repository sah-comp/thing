<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rückruf vereinbaren</title>
  <link rel="stylesheet" href="default.css?v=0.0.2">
</head>

<body>
  <header role="banner">
    <nav role="navigation">
      <a href="index.html" class="backward">Startseite</a>
    </nav>
  </header>
  <main role="main">
    <h1>Rückruf vereinbaren</h1>
    <p>Füllen Sie das Formular aus, um einen Rückruf zu vereinbaren. Ich melde mich zum gewählten Zeitpunkt.</p>
    <?php
    session_start();
    $recipient = 'info@sah-company.com'; // Change to your desired email
    if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
      die('<h2>Ungültige Empfängeradresse.</h2>');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Nonce validation for CSRF protection
      $nonce = $_POST['callback_nonce'] ?? '';
      if (empty($_SESSION['callback_nonce']) || $nonce !== $_SESSION['callback_nonce']) {
        echo '<h2>Ungültige oder abgelaufene Anfrage (CSRF-Schutz).</h2>';
      } else {
        $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');

        // Validate date and time are not before now
        $dateInput = trim($_POST['date'] ?? '');
        $timeInput = trim($_POST['time'] ?? '');
        $dateTimeValid = true;

        if ($dateInput) {
          // If time is provided, combine date and time, else use date only
          $dateTimeStr = $dateInput;
          if ($timeInput) {
            // Try to extract a time (e.g. "14:00" from "14:00 - 16:00 Uhr")
            if (preg_match('/(\d{1,2}:\d{2})/', $timeInput, $matches)) {
              $dateTimeStr .= ' ' . $matches[1];
            }
          }
          $userDateTime = strtotime($dateTimeStr);
          if ($userDateTime === false || $userDateTime < time()) {
            $dateTimeValid = false;
          }
        }

        if (!$dateTimeValid) {
          echo '<h2>Das gewählte Datum und die Uhrzeit dürfen nicht in der Vergangenheit liegen.</h2>';
          return;
        }
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars(trim($_POST['date'] ?? ''), ENT_QUOTES, 'UTF-8');
        $time = htmlspecialchars(trim($_POST['time'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        if ($name && $phone && $date) {
          unset($_SESSION['callback_nonce']);
          $subject = "Rückruf-Anfrage von $name";
          $body = "Name: $name\n" .
            "Telefon: $phone\n" .
            "Bevorzugtes Datum: $date\n" .
            "Bevorzugte Zeit: $time\n" .
            "Nachricht: $message\n";
          $from = 'noreply@' . preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']);
          $headers = "From: $from\r\n" .
            "Reply-To: $recipient\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-Type: text/plain; charset=UTF-8\r\n";
          $mailSuccess = false;
          try {
            $mailSuccess = mail($recipient, $subject, $body, $headers);
          } catch (Exception $e) {
            $mailSuccess = false;
          }
          if ($mailSuccess) {
            echo '<h2>Vielen Dank! Ihre Anfrage wurde gesendet.</h2>';
          } else {
            echo '<h2>Fehler beim Senden der Anfrage. Bitte versuchen Sie es später erneut oder kontaktieren Sie uns direkt.</h2>';
          }
        } else {
          echo '<h2>Bitte füllen Sie alle Pflichtfelder aus.</h2>';
        }
      }
    } else {
      if (empty($_SESSION['callback_nonce'])) {
        $_SESSION['callback_nonce'] = bin2hex(random_bytes(16));
      }
      $nonce = $_SESSION['callback_nonce'];
    ?>
  <form action="callback.php" method="post" class="callback-form" accept-charset="UTF-8" role="form">
        <label for="name">Ihr Name</label>
        <input type="text" id="name" name="name" required placeholder="Max Mustermann" tabindex="0">

        <label for="phone">Telefonnummer</label>
        <input type="tel" id="phone" name="phone" required pattern="[0-9+\s\-]+" placeholder="+49 123 4567890" tabindex="0">

        <label for="date">Bevorzugtes Datum</label>
        <input type="date" id="date" name="date" required placeholder="TT.MM.JJJJ" pattern="^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d\d$" tabindex="0">

        <label for="time">Bevorzugte Rückrufzeit</label>
        <input type="text" id="time" name="time" placeholder="z.B. 14:00 - 16:00 Uhr" tabindex="0">

        <label for="message">Kurze Nachricht</label>
        <textarea id="message" name="message" rows="3" maxlength="300" placeholder="Ihre Nachricht (optional)" tabindex="0"></textarea>

        <input type="hidden" name="callback_nonce" value="<?php echo htmlspecialchars($nonce); ?>">
        <button class="button-forward" type="submit" tabindex="0">Rückruf anfordern</button>
      </form>
    <?php }
    ?>
  </main>
  <footer role="contentinfo">
    <nav role="navigation">
      <a href="legal.html" class="forward">Lesen Sie hier Impressum und Rechtliches</a>
    </nav>
  </footer>
  <script>
    document.addEventListener('keydown', function(e) {
      if (e.shiftKey && e.metaKey && e.key.toLowerCase() === 'd') {
        var form = document.querySelector('.callback-form');
        if (form) {
          form.elements['name'].value = 'Max Mustermann';
          form.elements['phone'].value = '+49 123 4567890';
          // Fill today's date in DD.MM.YYYY format
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0');
          var yyyy = today.getFullYear();
          form.elements['date'].value = dd + '.' + mm + '.' + yyyy;
          form.elements['time'].value = '14:00 - 16:00 Uhr';
          form.elements['message'].value = 'Bitte um Rückruf bezüglich meines Anliegens.';
        }
      }
    });
  </script>
</body>

</html>