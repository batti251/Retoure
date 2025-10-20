<?php
session_start();
$msg = $_SESSION['flash_ok'] ?? null;
unset($_SESSION['flash_ok']);

if (!$msg) {
    header('Location: /index.html', true, 302); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="assets/logo/" type="image/x-icon">
    <link rel="stylesheet" href="assets/fonts.css">
    <script src="../script.js"></script>
</head>
<body>
     <div class="grid">
        <div class="logo">
            <img src="assets/logo/" alt="">
        </div>
        <div class="title-head d-flex d-center">
            <h1>Retoure</h1>
        </div>
        <div class="side"></div>

    <main class="d-flex d-center d-column text-center">
        <h1 class="t-black">Vielen Dank</h1>
        <p>Ihre Anfrage wurde an uns übermittelt
            <br><br>
            Sie können die Seite schließen
            <br><br>
             Oder Besuchen Sie gerne unsere <a href="http://">Website</a>
            </p>
    </main>

        <footer>
        <div class="footer-links gap-24">
          <a href="/assets/html/legal-notice.html">Impressum</a>
          <a href="/assets/html/policy-note.html">Datenschutzerklärung</a>
        </div>
    </footer>
    </div>
</body>
</html>