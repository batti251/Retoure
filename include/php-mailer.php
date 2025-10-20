<?php
$rootDir    = dirname(__DIR__);  
$includeDir = $rootDir . '/include';

require_once $includeDir . '/PHPMailer-master/src/Exception.php';
require_once $includeDir . '/PHPMailer-master/src/PHPMailer.php';
require_once $includeDir . '/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



$cfg = require $includeDir . '/authentification.php';

function sendMail($fromName, $fromEmail, $subject, $body, $attachments = []) {
   global $cfg;
    $mail = new PHPMailer(true);
       
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php-error.log');

try {
    $mail->isSMTP();
    $mail->Host       = $cfg['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $cfg['user'];
    $mail->Password   = $cfg['pass'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPOptions = [
    'ssl' => [
    'verify_peer'       => true,
    'verify_peer_name'  => true,
    'allow_self_signed' => false,
  ],
];
    $mail->Port       = (int)$cfg['port'];
    $mail->CharSet  = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->isHTML(true);
    $mail->setFrom($cfg['from'], $fromName);
    $mail->addAddress('');       // add target-mailaddress here
    $mail->addReplyTo($fromEmail, $fromName);
    $mail->Subject = $subject ?: '(Kein Betreff)';
    $mail->Body    = $body ?: '(Kein Inhalt)';
    $mail->AltBody = strip_tags($mail->Body);
    
    // 6) Optional: Attachments hinzufügen
         foreach ($attachments as $file) {
            if (!empty($file['path']) && is_readable($file['path'])) {
                $mail->addAttachment($file['path'], $file['name'] ?? basename($file['path']));
            } elseif (!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
                $mail->addAttachment($file['tmp_name'], $file['name'] ?? basename($file['tmp_name']));
            }
        }

    $mail->send();
            return [true, 'Mail wurde erfolgreich verschickt.'];
        } catch (Exception $e) {
            return [false, 'Mail konnte nicht gesendet werden: ' . $mail->ErrorInfo];
        }
}
?>

