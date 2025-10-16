<?php 
// checks MimeType of file, that are allowed (pdf)
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
// NEU: $_FILES['fileInputs'] in flaches Array verwandeln
function normalizeFilesArray(array $filePost): array {
    if (!isset($filePost['name'])) return [];
    if (is_array($filePost['name'])) {
        $out = [];
        $count = count($filePost['name']);
        $keys  = array_keys($filePost);
        for ($i = 0; $i < $count; $i++) {
            foreach ($keys as $k) {
                $out[$i][$k] = $filePost[$k][$i] ?? null;
            }
        }
        return $out;
    }
    return [$filePost];
}
?>

<?php
// checks type of image files, that are allowed (jpg, png, gif, webp, heic, heif, avif)
function checkImageMimeType($fileTmpPath){
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $fileTmpPath);
    finfo_close($finfo);

    $allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp",  'image/heic', 'image/heif', 'image/avif'];
    return in_array($mimeType, $allowedTypes, true);
}
?>

<?php
// handles multiple image uploads from <input name="fileInputs[]">
function uploadImageFiles($customerno, $deliverynote, string $fieldName = 'fileInputs'): array {
   // checks, if files were uploaded without errors
    // returns array of [bool $success, string $messageOrPath] tuples
    if (!isset($_FILES[$fieldName])) {
        return [[false, "Upload-Feld '$fieldName' nicht gefunden."]];
    }

    // set base directory => web
    // set target directory for upload => web/uploads/
    $baseDir   = dirname(__DIR__);
    $uploadDir = $baseDir . '/uploads/';
    if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0755, true); }

    // array to collect img-file-results
    $results = [];

    // process each uploaded file
    // 
    foreach (normalizeFilesArray($_FILES[$fieldName]) as $f) {
        $err = (int)($f['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($err === UPLOAD_ERR_NO_FILE) {
            continue;
        }
        if ($err !== UPLOAD_ERR_OK) {
            $results[] = [false, "Upload-Fehler (Code $err)"];
            continue;
        }

        //declares variables
        $tmp  = (string)($f['tmp_name'] ?? '');
        $name = (string)($f['name'] ?? '');
        $size = (int)   ($f['size'] ?? 0);

         // checks type and size of InputFile => pdf
        if (!checkImageFileSize($size))   { $results[] = [false, "Datei zu groß (Limit aktuell 1 MB)."]; continue; }
        if (!checkImageMimeType($tmp)) { $results[] = [false, "Nur Bilddateien (jpg, png, gif, webp) sind erlaubt."]; continue; }

        // set new file name
        // sets destionation path
        $safeName  = changeUmlaute($name);
        $timestamp = timestamp();
        $destPath  = $uploadDir . $customerno . "_" . $deliverynote . "_" . $timestamp . "_" . $safeName;

        // additional security: ensure the upload directory exists
        // then move the uploaded file
        if (move_uploaded_file($tmp, $destPath)) {
            $results[] = [true, $destPath];
        } else {
            $results[] = [false, "move_uploaded_file() ist fehlgeschlagen."];
        }
    }

    return $results;
}
?>


<?php
// handles single file upload from <input name="fileInput">
function uploadFile($customerno, $deliverynote){
    // checks, if a file was uploaded without errors
    // if no file was uploaded or there was an error, return false with message
    if (!isset($_FILES["fileInput"]) || $_FILES["fileInput"]["error"] !== UPLOAD_ERR_OK) {
        return [false, "Keine Datei hochgeladen oder Upload-Fehler."];
    }

    //declares variables
    $fileTmpPath = $_FILES["fileInput"]["tmp_name"];
    $fileName    = $_FILES["fileInput"]["name"];
    $fileSize    = (int)$_FILES["fileInput"]["size"];

    // checks type and size of InputFile => pdf
    if (!checkFileSize($fileSize)) {
        return [false, "Datei zu groß (Limit aktuell 1 MB)."];
    }
    if (!checkMimeType($fileTmpPath)) {
        return [false, "Nur PDF-Dateien sind erlaubt."];
    }

    // set base directory => web
    // set target directory for upload => web/uploads/
    $baseDir   = dirname(__DIR__); // /web
    $uploadDir = $baseDir . '/uploads/';

     // set new file name
     // sets destionation path
    $safeName  = changeUmlaute($fileName);
    $timestamp = timestamp();
    $destPath  = $uploadDir . $customerno . "_" . $deliverynote . "_" . $timestamp . "_" . $safeName;

    // additional security: ensure the upload directory exists
    // then move the uploaded file
   if (move_uploaded_file($fileTmpPath, $destPath)) {
        $publicUrl = '/uploads/' . basename($destPath);
        return [true, $destPath]; // oder [true, $publicUrl] – je nach Bedarf
    } else {
        return [false, "move_uploaded_file() ist fehlgeschlagen."];
    }
}
?>

<?php 
// replaces German Umlaute in filenames
// and replaces special characters with underscores
// also removes multiple underscores and leading/trailing dots/underscores
// to make filenames safer for various filesystems
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
// sets a timestamp for file renaming
function timestamp(){
    $timestamp = date("Ymd_His");
    return $timestamp;
}
?>


<?php
// checks Size of PDF files
const MAX_PDF_SIZE = 1024 * 1024;
function checkFileSize($fileSize){
    if ($fileSize <= MAX_PDF_SIZE) {
        echo $fileSize;
        return true;
    } else echo $fileSize; return false;
}


// checks Size of image files (jpg, png, gif, webp)
const MAX_IMAGE_SIZE = 10 * 1024 * 1024;
function checkImageFileSize($fileSize){
    return $fileSize > 0 && $fileSize <= MAX_IMAGE_SIZE;
}


?>