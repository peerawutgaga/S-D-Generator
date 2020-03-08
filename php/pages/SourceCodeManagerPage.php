<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/utilities/LocalFileManager.php";
if (isset($_POST['functionName'])) {
    if ($_POST['functionName'] == "getFileList") {
        SourceCodeManagerPage::getFileList();
    }
    else if ($_POST['functionName'] == "deleteFile" && isset($_POST['fileId'])) {
        SourceCodeManagerPage::deleteFile($_POST['fileId']);
    }
    else if ($_POST['functionName'] == "renameFile" && isset($_POST['fileId'])&& isset($_POST['newFilename'])) {
        SourceCodeManagerPage::renameFile($_POST['fileId'], $_POST['newFilename']);
    }
}

class SourceCodeManagerPage
{

    public static function getFileList()
    {
        $fileList = SourceCodeService::selectAllFromSourceCode();
        echo json_encode($fileList);
    }

    public static function deleteFile($fileId)
    {
        $success = SourceCodeService::deleteFromSourceCodeByFileId($fileId);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
    }

    public static function renameFile($fileId, $newFilename)
    {
        $success = SourceCodeService::updateSourceCodeFileSetFilenameByFileId($newFilename, $fileId);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
    }
}
?>