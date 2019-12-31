<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Logger.php";
class SourceCodeService
{
    private static function executeSelectStatement($conn, $sql)
    {
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService", $e->getMessage());
        } finally{
            unset($conn);
        }
        return $result;
    }
    
    private static function executeInsertStatement($conn, $sql)
    {
        $lastInsertId = - 1;
        try {
            $sql->execute();
            $lastInsertId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService", $e->getMessage());
        } finally{
            unset($conn);
        }
        return $lastInsertId;
    }
    
    private static function executeDeleteStatement($conn, $sql)
    {
        return self::executeSqlStatementWithSuccessFlag($conn, $sql);
    }
    
    private static function executeUpdateStatement($conn, $sql)
    {
        return self::executeSqlStatementWithSuccessFlag($conn, $sql);
    }
    
    private static function executeSqlStatementWithSuccessFlag($conn, $sql)
    {
        $result = false;
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Logger::logDatabaseError("SourceCodeService", $e->getMessage());
        } finally{
            unset($conn);
        }
        return $result;
    }
    public static function insertIntoSourceCodeFile($filename,$filePayload,$language,$sourceType){
        $conn = Database::getConnection();
        $language = strtoupper($language);
        $sourceType = strtoupper($sourceType);
        $sql = $conn->prepare("INSERT INTO `code.sourcecodefile` (`filename`, `filePayload`,`language`,`sourceType`) VALUES(:filename,:filePayload,:language,:sourceType)");
        $sql->bindParam(":filename", $filename);
        $sql->bindParam(":filePayload", $filePayload);
        $sql->bindParam(":language", $language);
        $sql->bindParam(":sourceType", $sourceType);
        $fileId = self::executeInsertStatement($conn, $sql);
        return $fileId;
    }
    public static function selectFromSourceCodeByFilename($filename){
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `code.sourcecodefile` WHERE `filename` = :filename");
        $sql->bindParam(":filename", $filename);
        return self::executeSelectStatement($conn, $sql);
    }
    public static function updateSourceCodeFileSetFilePayloadByFileId($filePayload,$fileId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("UPDATE `code.sourcecodefile` SET filePayload = :filePayload WHERE fileId = :fileId");
        $sql->bindParam(":filePayload", $filePayload);
        $sql->bindParam(":fileId", $fileId);
        $result = self::executeUpdateStatement($conn, $sql);
        return $result;
    }
    public static function updateSouceCodeFileSetFilenameByFileId($filename,$fileId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("UPDATE `code.sourcecodefile` SET filename = :filename WHERE fileId = :fileId");
        $sql->bindParam(":filename", $filename);
        $sql->bindParam(":fileId", $fileId);
        $result = self::executeUpdateStatement($conn, $sql);
        return $result;
    }
}
?>
