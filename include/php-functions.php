<?php 
function checkMimeType($fileTmpPath){
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $fileTmpPath);
finfo_close($finfo);

$allowedTypes = ["application/pdf"];
if (in_array($mimeType, $allowedTypes)) {
    return true;
} else {
    return false;
}}

?>


<?php
function showFileInfo(){
echo "<pre>";
print_r($_FILES["fileInput"]);
echo "</pre>";
}
?>



<?php
function uploadFile($customerno, $deliverynote){
    if (!isset($_FILES["fileInput"]) || $_FILES["fileInput"]["error"] !== UPLOAD_ERR_OK) {
        return [false, "Keine Datei hochgeladen oder Upload-Fehler."];
    }

    $fileTmpPath = $_FILES["fileInput"]["tmp_name"];
    $fileName    = $_FILES["fileInput"]["name"];
    $fileSize    = (int)$_FILES["fileInput"]["size"];

    if (!checkFileSize($fileSize)) {
        return [false, "Datei zu groß (Limit aktuell 1 MB)."];
    }
    if (!checkMimeType($fileTmpPath)) {
        return [false, "Nur PDF-Dateien sind erlaubt."];
    }

    $baseDir   = dirname(__DIR__);
    $uploadDir = $baseDir . '/uploads/';

    $safeName  = changeUmlaute($fileName);
    $timestamp = timestamp();
    $destPath  = $uploadDir . $customerno . "_" . $deliverynote . "_" . $timestamp . "_" . $safeName;


   if (move_uploaded_file($fileTmpPath, $destPath)) {
        $publicUrl = '/uploads/' . basename($destPath);
        return [true, $destPath]; 
    } else {
        return [false, "move_uploaded_file() ist fehlgeschlagen."];
    }
}
?>

<?php 
function changeUmlaute($fileName){
    $umlaute = [
        'ä' => 'ae', 'Ä' => 'Ae',
        'ö' => 'oe', 'Ö' => 'Oe',
        'ü' => 'ue', 'Ü' => 'Ue', 
        'ß' => 'ss'
    ];
    $fileName = strtr($fileName, $umlaute);
    $fileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
    $fileName = preg_replace('/_+/', '_', $fileName);
    $fileName = trim($fileName, '._');
    return $fileName;
}
?>


<?php
function timestamp(){
    $timestamp = date("Ymd_His");
    return $timestamp;
}
?>


<?php
const MAX_PDF_SIZE = 1024 * 1024;

function checkFileSize($fileSize){
    if ($fileSize <= MAX_PDF_SIZE) {
        echo $fileSize;
        return true;
    } else echo $fileSize; return false;
}
?>