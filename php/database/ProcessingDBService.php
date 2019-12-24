<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Logger.php";
class ProcessingDBService{
    
    public static function insertIntoProcessingObject($objectId,$objectIdStr){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `processing.objectnode` (`objectId`, `objectIdStr`) VALUES(:objectId,:objectIdStr)");
        $sql->bindParam(":objectId", $objectId);
        $sql->bindParam(":objectIdStr", $objectIdStr);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService",$e->getMessage());
        } finally{
             unset($conn);
        }
    }
    public static function insertIntoProcessingMessage($messageId,$msgIdStr,$returnMsgId,$fromObjectId,$toObjectId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `processing.message` (`messageId`, `msgIdStr`, `returnMsgId`, `fromObjectId`, `toObjectId`) 
VALUES(:messageId,:msgIdStr,:returnMsgId,:fromObjectId,:toObjectId)");
        $sql->bindParam(":messageId", $messageId);
        $sql->bindParam(":msgIdStr", $msgIdStr);
        $sql->bindParam(":returnMsgId", $returnMsgId);
        $sql->bindParam(":fromObjectId", $fromObjectId);
        $sql->bindParam(":toObjectId", $toObjectId);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        } finally{
             unset($conn);
        }
    }
    public static function cleanProcessingDatabase(){
        $conn = Database::getConnection();
        self::deleteAllFromProcessingMessage($conn);
        self::deleteAllFromProcessingObjectNode($conn);
        self::deleteAllFromProcessingInheritance($conn);
        unset($conn);
    }
    private static function deleteAllFromProcessingMessage($conn){     
        $sql = $conn->prepare("DELETE FROM `processing.message`");
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        }
    }
    private static function deleteAllFromProcessingObjectNode($conn){
        $sql = $conn->prepare("DELETE FROM `processing.objectnode`");
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        }
    }
    private static function deleteAllFromProcessingInheritance($conn){
        $sql = $conn->prepare("DELETE FROM `processing.inheritance`");
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        }
    }
    public static function selectObjectIdByObjectIdStr($objectIdStr){
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT objectId FROM `processing.objectnode` WHERE objectIdStr = :objectIdStr");
        $sql->bindParam(':objectIdStr', $objectIdStr);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        }finally{
            unset($conn);
        }
        return $result;
    }
    public static function selectMessageIdByMessageIdStr($msgIdStr){
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT messageId FROM `processing.message` WHERE msgIdStr = :msgIdStr");
        $sql->bindParam(':msgIdStr', $msgIdStr);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService", $e->getMessage());
        }finally{
            unset($conn);
        }
        return $result;
    }
    public static function insertIntoProcessingInheritance($realizationId, $parentId,$childId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `processing.inheritance` (`realizationId`,`parentId`, `childId`) VALUES(:realizationId,:parentId,:childId)");
        $sql->bindParam(":realizationId", $realizationId);
        $sql->bindParam(":parentId", $parentId);
        $sql->bindParam(":childId", $childId);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ProcessingDBService",$e->getMessage());
        } finally{
            unset($conn);
        }
    }
}
?>