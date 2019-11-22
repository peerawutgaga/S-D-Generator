<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Script.php";
class CallGraphProcessingService{
    public static function insertIntoProcessingObject($objectId,$objectIdStr){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `processing.objectnode` (`objectId`, `objectIdStr`) VALUES(:objectId,:objectIdStr)");
        $sql->bindParam(":objectId", $objectId);
        $sql->bindParam(":objectIdStr", $objectIdStr);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
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
            Script::consoleLog($e->getMessage());
        } finally{
             unset($conn);
        }
    }
    public static function cleanProcessingDatabase(){
        $conn = Database::getConnection();
        self::deleteAllFromProcessingMessage($conn);
        self::deleteAllFromProcessingObjectNode($conn);
        unset($conn);
    }
    private static function deleteAllFromProcessingMessage($conn){     
        $sql = $conn->prepare("DELETE FROM `processing.message`");
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        }
    }
    private static function deleteAllFromProcessingObjectNode($conn){
        $conn = Database::getConnection();
        $sql = $conn->prepare("DELETE FROM `processing.objectnode`");
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
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
            Script::consoleLog($e->getMessage());
        }finally{
            unset($conn);
        }
        return $result;
    }
}
?>