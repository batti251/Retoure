<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: /form.php'); exit;
}

$rootDir    = dirname(__DIR__);
$includeDir = $rootDir . '/include';

require_once $includeDir . '/php-functions.php';
require_once $includeDir . '/php-mailer.php';
require_once $includeDir . '/php-validation.php';

$customerno = $customername = $deliverynote = $disposalfile = $contactname = $contactnumber = $contactmail = "";
$attachments = [];



if ($_SERVER["REQUEST_METHOD"] === "POST") {
validate_inputs($_POST["integerInput"][0], $_POST["integerInput"][1], $_POST["phoneInput"], $_POST["mailInput"], $_POST["reason"]);

$customerno = ($_POST["integerInput"][0]);
$customername = ($_POST["stringInput"][0]);
$deliverynote = ($_POST["integerInput"][1]);
$reason = ($_POST["reason"]);
$miscellaneous = ($_POST["miscellaneoustext"]);
$contactname =($_POST["stringInput"][1]);
$contactnumber =($_POST["phoneInput"]);
$contactmail =($_POST["mailInput"]);
$values = [$customerno, $customername, $deliverynote, $reason, $miscellaneous, $contactname, $contactnumber, $contactmail];



 // build email content
    $subject = "Neue Retoure von $customername (Kd.-Nr. $customerno)";
    $body = "
        <h3>Neue Anfrage</h3>
        <p><strong>Kundennummer:</strong> " . htmlspecialchars($customerno) . "</p>
        <p><strong>Kunde:</strong> " . htmlspecialchars($customername) . "</p>
        <p><strong>Lieferscheinnummer:</strong> " . htmlspecialchars($deliverynote) . "</p>
        <p><strong>Grund:</strong> " . translateReason($reason) . "</p>
        <p><strong>Sonstiges:</strong> " . nl2br(htmlspecialchars($miscellaneous)) . "</p>
        <hr>
        <p><strong>Kontaktname:</strong> " . htmlspecialchars($contactname) . "</p>
        <p><strong>Telefon:</strong> " . htmlspecialchars($contactnumber) . "</p>
        <p><strong>E-Mail:</strong> " . htmlspecialchars($contactmail) . "</p>
    ";


  // Reply-To from contact info
    $fromName  = $contactname ?: $customername ?: 'Webformular';
    $fromEmail = $contactmail ?: 'noreply@example.com';

    
// upload single PDF from fileInput
[$okUpload, $uploadResult] = uploadFile($customerno, $deliverynote);
if (!$okUpload) {
    echo "❌ Upload-Fehler: " . htmlspecialchars($uploadResult);
} else {
     $attachments[] = [
        'path' => $uploadResult,
        'name' => basename($uploadResult),
    ];
} 
// upload multiple images from fileInputs[]
if (isset($_FILES['fileInputs'])) {
    $imgResults = uploadImageFiles($customerno, $deliverynote, 'fileInputs');
    foreach ($imgResults as $res) {
        [$ok, $msgOrPath] = $res;
        if ($ok) {
            $attachments[] = [
                'path' => $msgOrPath,
                'name' => basename($msgOrPath),
            ];
        } else {
            echo "Upload-Fehler (Bilder): " . htmlspecialchars($msgOrPath);
        }
    }
}
 


    // send mail
    // redirects to success page if mail was sent
    [$ok, $msg] =  sendMail($fromName, $fromEmail, $subject, $body, $attachments) ;
    $_SESSION['flash_ok'] = 'Ihre Anfrage wurde erfolgreich übermittelt.';
header('Location: /confirmation.php', true, 303);
exit;
}
?>