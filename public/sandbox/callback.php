<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rückruf vereinbaren</title>
  <link rel="stylesheet" href="default.css?v=0.0.2">
</head>

<body>
  <header>
        <nav>
            <a href="index.html" class="backward">Startseite</a>
        </nav>
    </header>
  <main>
    <h1>Rückruf vereinbaren</h1>
  <?php
    session_start();
    if (empty($_SESSION['callback_nonce'])) {
      $_SESSION['callback_nonce'] = bin2hex(random_bytes(16));
    }
    $nonce = $_SESSION['callback_nonce'];
  ?>
  <form action="callback_process.php" method="post" class="callback-form" accept-charset="UTF-8">
  <label for="name">Ihr Name</label>
    <input type="text" id="name" name="name" required placeholder="Max Mustermann" tabindex="1">

  <label for="phone">Telefonnummer</label>
    <input type="tel" id="phone" name="phone" required pattern="[0-9+\s\-]+" placeholder="+49 123 4567890" tabindex="2">

  <label for="date">Bevorzugtes Datum</label>
    <input type="date" id="date" name="date" required placeholder="TT.MM.JJJJ" pattern="^(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d\d$" tabindex="3">

  <label for="time">Bevorzugte Rückrufzeit</label>
    <input type="text" id="time" name="time" placeholder="z.B. 14:00 - 16:00 Uhr" tabindex="4">

  <label for="message">Kurze Nachricht</label>
    <textarea id="message" name="message" rows="3" maxlength="300" placeholder="Ihre Nachricht (optional)" tabindex="5"></textarea>

  <input type="hidden" name="callback_nonce" value="<?php echo htmlspecialchars($nonce); ?>">
  <button class="button-forward" type="submit" tabindex="6">Rückruf anfordern</button>
  </form>
  </main>
  <footer>
    <nav>
      Lesen Sie hier <a href="legal.html" class="forward">Impressum und Rechtliches</a>
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