 <?php
 
require_once "Database.php";
include_once "../utilities/Script.php";

class CallGraphService
{

    public static function insertIntoCallGraph($callGraphName, $filePath)
    {
        $conn = Database::getConnection();
        $callGraphId = - 1;
        $sql = $conn->prepare("INSERT INTO `callgraph.graph` (`callGraphName`, `filePath`) VALUES(:callGraphName,:filePath)");
        $sql->bindParam(":callGraphName", $callGraphName);
        $sql->bindParam(":filePath", $filePath);
        try {
            $sql->execute();
            $callGraphId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $callGraphId;
    }

    public static function insertIntoObjectNode($callGraphID, $objectName, $baseIdentifier)
    {
        $conn = Database::getConnection();
        $objectId = - 1;
        $sql = $conn->prepare("INSERT INTO `callgraph.objectNode`(`callGraphId`, `objectName`, `baseIdentifier`) VALUES(:callGraphId,:objectName,:baseIdentifier)");
        $sql->bindParam(":callGraphId", $callGraphID);
        $sql->bindParam(":objectName", $objectName);
        $sql->bindParam(":baseIdentifier", $baseIdentifier);
        try {
            $sql->execute();
            $objectId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $objectId;
    }
    public static function insertIntoGateObject($objectId,$callGraphId,$gateMsgId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.gateObject`(`objectId`, `callGraphId`, `gateMsgId`) VALUES(:objectId,:callGraphId,:gateMsgId)");
        $sql->bindParam(":callGraphId", $callGraphId);
        $sql->bindParam(":objectId", $objectId);
        $sql->bindParam(":gateMsgId", $gateMsgId);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
    }
    public static function insertIntoMessage($fromObjectId, $toObjectId, $messageName, $messageType)
    {
        $conn = Database::getConnection();
        $messageId = - 1;
        $messageType = strtoupper($messageType);
        $sql = $conn->prepare("INSERT INTO `callgraph.message`(`fromObjectId`, `toObjectId`,`messageName`, `messageType`) 
                VALUES(:fromObjectId, :toObjectId, :messageName, :messageType)");
        $sql->bindParam(":fromObjectId", $fromObjectId);
        $sql->bindParam(":toObjectId", $toObjectId);
        $sql->bindParam(":messageName", $messageName);
        $sql->bindParam(":messageType", $messageType);
        try {
            $sql->execute();
            $messageId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $messageId;
    }
    public static function insertIntoReturnMessage($messageId,$dataType,$parentMessageId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.returnmessage`(`messageId`, `dataType`,`parentMessageId`)
                VALUES(:messageId, :dataType, :parentMessageId)");
        $sql->bindParam(":messageId", $messageId);
        $sql->bindParam(":dataType", $dataType);
        $sql->bindParam(":parentMessageId", $parentMessageId);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
    }

    public static function insertIntoArgument($messageId, $arguName, $seqIdx, $dataType)
    {
        $conn = Database::getConnection();
        $arguId = - 1;
        $sql = $conn->prepare("INSERT INTO `callgraph.argument`(`messageId`, `arguName`, `seqIdx`, `dataType`) 
                VALUES(:messageID, :arguName, :seqIdx, :dataType)");
        $sql->bindParam(":messageID", $messageId);
        $sql->bindParam(":arguName", $arguName);
        $sql->bindParam(":seqIdx", $seqIdx);
        $sql->bindParam(":dataType", $dataType);
        try {
            $sql->execute();
            $arguId = $sql->lastInsertId();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $arguId;
    }
    public static function insertIntoGuardCondition($messageId,$statement){
        $conn = Database::getConnection();
        $guardCondId = - 1;
        $sql = $conn->prepare("INSERT INTO `callgraph.guardcondition`(`messageId`,`statement`)
        VALUES(:messageId,:statement)");
        $sql->bindParam(":messageId",$messageId);
        $sql->bindParam(":statement",$statement);
        try {
            $sql->execute();
            $guardCondId = $sql->lastInsertId();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $guardCondId;
        
    }
    public static function selectFromGraphByCallGraphId($callGraphID)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.graph` WHERE `callGraphId` = :callGraphID");
        $sql->bindParam(':callGraphID', $callGraphID);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function selectAllFromGraph()
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.graph`");
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function selectFromObjectNodeByCallGraphId($callGraphId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE callGraphID = :callGraphID");
        $sql->bindParam(':callGraphID', $callGraphId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function selectObjectNodeByObjectID($objectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE objectId = :objectId");
        $sql->bindParam(':objectId', $objectId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function selectMessageByFromObjectID($fromObjectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `fromObjectId` = :fromObjectId");
        $sql->bindParam(':fromObjectId', $fromObjectId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function selectMessageByToObjectID($toObjectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `toObjectId` = :toObjectId");
        $sql->bindParam(':toObjectId', $toObjectId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }
    public static function selectMessageByFromAndToObjectId($fromObjectId, $toObjectId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `fromObjectId` = :fromObjectId AND `toObjectId` = :toObjectId");
        $sql->bindParam(':fromObjectId', $fromObjectId);
        $sql->bindParam(':toObjectId', $toObjectId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function deleteFromGraphByCallGraphId($callGraphId)
    {
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("DELETE FROM `callgraph.graph` WHERE `callGraphId` = :callGraphId");
        
        $sql->bindParam(":callGraphId", $callGraphId);
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }

    public static function updateGraphSetCallGraphNameByCallGraphId($callGraphId, $callGraphName)
    {
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("UPDATE `callgraph.graph` SET callGraphName = :callGraphName WHERE callGraphId = :callGraphId");
        $sql->bindParam(":callGraphId", $callGraphId);
        $sql->bindParam(":callGraphName", $callGraphName);
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            $conn = null;
        }
        return $result;
    }
}

?>