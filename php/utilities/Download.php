<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/utilities/LocalFileManager.php";

$url = $_SERVER[REQUEST_URI];
$fileId = substr($url, strrpos($url, "sourcecode=") + strlen("sourcecode="));
if ($fileId == "zip") {
    $file = $root . "/DownloadTemp/Source_Code_Files.zip";
} else {
    $file = LocalFileManager::prepareFile($fileId);
}
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    LocalFileManager::delete($file);
}
?>