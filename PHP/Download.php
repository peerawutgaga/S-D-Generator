<?php
    require_once "LocalFileManager.php";
    $url = $_SERVER[REQUEST_URI];
    $filename = substr($url,29);
    $file = LocalFileManager::prepareDownload($filename);
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        LocalFileManager::delete($file);
    }
?>