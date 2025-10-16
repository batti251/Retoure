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
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="assets/fonts.css">
</head>
<body>
    <header class="d-flex d-center">
        <div class="d-flex gap-24">
            <div class="logo"></div>
            <div class="font-24px"></div>
        </div>
    </header>


    <div class="d-flex d-center d-column text-center">
        <h1>Vielen Dank</h1>
        <p>Ihre Anfrage wurde an uns übermittelt
            <br><br>
            Sie können die Seite schließen
        </p>
    </div>
</body>
</html>