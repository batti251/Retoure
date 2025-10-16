<?php
session_start();
$msg = $_SESSION['flash_ok'] ?? null;
unset($_SESSION['flash_ok']);

if (!$msg) {
    header('Location: /index.html', true, 302); 
    exit;
}
?>


<!doctype html>
<html lang="de"><body>
  <h1>Danke!</h1>
  <p><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
</body></html>