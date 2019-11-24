<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Logger.php";
class SourceCodeService
{
    public static function insertIntoSourceCodeFile($filename,$filePayload,$language,$sourceType){
        $conn = Database::getConnection();
        $fileId = - 1;
        $language = strtoupper($language);
        $sourceType = strtoupper($sourceType);
        $sql = $conn->prepare("INSERT INTO `code.sourcecodefile` (`filename`, `filePayload`,`language`,`sourceType`) VALUES(:filename,:filePayload,:language,:sourceType)");
        $sql->bindParam(":filename", $filename);
        $sql->bindParam(":filePayload", $filePayload);
        $sql->bindParam(":language", $language);
        $sql->bindParam(":sourceType", $sourceType);
        try {
            $sql->execute();
            $fileId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $fileId;
    }
    public static function updateSourceCodeFileSetFilePayloadByFileId($filePayload,$fileId){
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("UPDATE `code.sourcecodefile` SET filePayload = :filePayload WHERE fileId = :fileId");
        $sql->bindParam(":filePayload", $filePayload);
        $sql->bindParam(":fileId", $fileId);
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
    public static function updateSouceCodeFileSetFilenameByFileId($filename,$fileId){
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("UPDATE `code.sourcecodefile` SET filename = :filename WHERE fileId = :fileId");
        $sql->bindParam(":filename", $filename);
        $sql->bindParam(":fileId", $fileId);
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
}
?>
