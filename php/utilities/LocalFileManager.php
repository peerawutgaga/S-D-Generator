<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/utilities/Logger.php";
if (isset($_POST['function']) && isset($_POST['fileList'])) {
    if ($_POST['function'] == "zip") {
        LocalFileManager::zip($_POST['fileList']);
    }
}

class LocalFileManager
{

    public static function delete($filePath)
    {
        $result = false;
        try{
            $result = unlink($filePath);
        }catch(Exception $e){
            $result = false;
            Logger::logInternalError("LocalFileManager", $e->getMessage());
        }finally{
            return $result;
        }
    }

    public static function copy($source, $dest)
    {
        // TODO Rewrite
        // return copy($source, $dest);
    }

    public static function prepareFile($fileId)
    {
        $file = SourceCodeService::selectFromSourceCodeByFileId($fileId)[0];
        $filePath = self::createFile($file["filename"], $file["filePayload"]);
        return $filePath;
    }

    private static function createFile($filename, $filePayload)
    {
        $outputPath = realpath($_SERVER["DOCUMENT_ROOT"]) . "/DownloadTemp/";
        if (! is_dir($outputPath)) {
            mkdir($outputPath);
        }
        $filePath = $outputPath . $filename;
        $file = fopen($filePath, "w");
        fwrite($file, $filePayload);
        fclose($file);
        return $filePath;
    }

    private static function getFilenameFromFilePath($filePath)
    {
        $startIdx = strripos($filePath, "/") + 1;
        $endIdx = strlen($filePath);
        return substr($filePath, $startIdx, $endIdx);
    }

    public static function zip($fileList)
    {
        $outputPath = realpath($_SERVER["DOCUMENT_ROOT"]) . "/DownloadTemp/";
        try {
            if (! is_dir($outputPath)) {
                mkdir($outputPath);
            }
            $zip = new ZipArchive();
            $zipFilename = $outputPath . "Source_Code_Files.zip";
            if ($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE) {
                echo "fail";
                return;
            }
            $fileList = explode(",", $fileList);
            $filePathList = array();
            foreach ($fileList as $fileId) {
                $filePath = self::prepareFile($fileId);
                $zip->addFile($filePath,self::getFilenameFromFilePath($filePath));
                array_push($filePathList,$filePath);
            }
            $zip->close();
            self::cleanUpTempFile($filePathList);
            echo "success";
        } catch (Exception $e) {
            Logger::logInternalError("LocalFileManager", $e->getMessage());
            echo "fail";
        }
    }
    private static function cleanUpTempFile($filePathList){
        foreach($filePathList as $filePath){
            self::delete($filePath);
        }
    }
}
?>