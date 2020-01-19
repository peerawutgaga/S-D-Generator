<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/SourceCodeService.php";
require_once "$root/php/utilities/LocalFileManager.php";
if (isset($_POST['function'])) {
    if ($_POST['function'] == "openFile" && isset($_POST['fileId'])) {
        CodeEditorPage::openFile($_POST['fileId']);
    }
    else if ($_POST['function'] == "rename" && isset($_POST['fileId']) && isset($_POST['newFilename'])) {
        CodeEditorPage::rename($_POST['fileId'],$_POST['newFilename']);
    }
    else if ($_POST['function'] == "saveFile" && isset($_POST['fileId']) && isset($_POST['filePayload'])) {
        CodeEditorPage::saveFile($_POST['fileId'], $_POST['filePayload']);
    }
}

class CodeEditorPage
{

    public static function openFile($fileId)
    {
        $file = SourceCodeService::selectFromSourceCodeByFileId($fileId);
        echo json_encode($file);
    }

    public static function rename($fileId, $newFilename)
    {
        $isSuccess = SourceCodeService::updateSourceCodeFileSetFilenameByFileId($newFilename, $fileId);
        if($isSuccess){
            echo "success";
        }else{
            echo "failed";
        }
    }
    public static function saveFile($fileId,$filePayload){
        $isSuccess = SourceCodeService::updateSourceCodeFileSetFilePayloadByFileId($filePayload, $fileId);
        if($isSuccess){
            echo "success";
        }else{
            echo "failed";
        }
    }
}
?>