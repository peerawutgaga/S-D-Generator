 <?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Logger.php";

class CallGraphService
{

    private static function executeSelectStatement($conn, $sql)
    {
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("CallGraphService", $e->getMessage());
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
            Logger::logDatabaseError("CallGraphService", $e->getMessage());
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
            Logger::logDatabaseError("CallGraphService", $e->getMessage());
        } finally{
            unset($conn);
        }
        return $result;
    }

    public static function insertIntoCallGraph($callGraphName, $filePath)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.graph` (`callGraphName`, `filePath`) VALUES(:callGraphName,:filePath)");
        $sql->bindParam(":callGraphName", $callGraphName);
        $sql->bindParam(":filePath", $filePath);
        $callGraphId = self::executeInsertStatement($conn, $sql);
        return $callGraphId;
    }

    public static function insertIntoObjectNode($callGraphId, $objectName, $baseIdentifier)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.objectNode`(`callGraphId`, `objectName`, `baseIdentifier`) VALUES(:callGraphId,:objectName,:baseIdentifier)");
        $sql->bindParam(":callGraphId", $callGraphId);
        $sql->bindParam(":objectName", $objectName);
        $sql->bindParam(":baseIdentifier", $baseIdentifier);
        $objectId = self::executeInsertStatement($conn, $sql);
        return $objectId;
    }

    public static function insertIntoGateObject($objectId, $callGraphId, $gateMsgId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.gateObject`(`objectId`, `callGraphId`, `gateMsgId`) VALUES(:objectId,:callGraphId,:gateMsgId)");
        $sql->bindParam(":callGraphId", $callGraphId);
        $sql->bindParam(":objectId", $objectId);
        $sql->bindParam(":gateMsgId", $gateMsgId);
        self::executeInsertStatement($conn, $sql);
    }

    public static function insertIntoMessage($fromObjectId, $toObjectId, $messageName, $messageType)
    {
        $conn = Database::getConnection();
        $messageType = strtoupper($messageType);
        $sql = $conn->prepare("INSERT INTO `callgraph.message`(`fromObjectId`, `toObjectId`,`messageName`, `messageType`) 
                VALUES(:fromObjectId, :toObjectId, :messageName, :messageType)");
        $sql->bindParam(":fromObjectId", $fromObjectId);
        $sql->bindParam(":toObjectId", $toObjectId);
        $sql->bindParam(":messageName", $messageName);
        $sql->bindParam(":messageType", $messageType);
        $messageId = self::executeInsertStatement($conn, $sql);
        return $messageId;
    }

    public static function insertIntoReturnMessage($messageId, $dataType, $isObject, $parentMessageId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.returnmessage`(`messageId`, `dataType`,`isObject`,`parentMessageId`)
                VALUES(:messageId, :dataType,:isObject ,:parentMessageId)");
        $sql->bindParam(":messageId", $messageId);
        $sql->bindParam(":dataType", $dataType);
        $sql->bindParam(":isObject", $isObject);
        $sql->bindParam(":parentMessageId", $parentMessageId);
        self::executeInsertStatement($conn, $sql);
    }

    public static function insertIntoArgument($messageId, $arguName, $seqIdx, $dataType, $isObject)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.argument`(`messageId`, `arguName`, `seqIdx`, `dataType`,`isObject`) 
                VALUES(:messageID, :arguName, :seqIdx, :dataType,:isObject)");
        $sql->bindParam(":messageID", $messageId);
        $sql->bindParam(":arguName", $arguName);
        $sql->bindParam(":seqIdx", $seqIdx);
        $sql->bindParam(":dataType", $dataType);
        $sql->bindParam(":isObject", $isObject);
        $arguId = self::executeInsertStatement($conn, $sql);
        return $arguId;
    }

    public static function insertIntoGuardCondition($messageId, $statement)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `callgraph.guardcondition`(`messageId`,`statement`)
        VALUES(:messageId,:statement)");
        $sql->bindParam(":messageId", $messageId);
        $sql->bindParam(":statement", $statement);
        $guardCondId = self::executeInsertStatement($conn, $sql);
        return $guardCondId;
    }

    public static function selectFromGraphByCallGraphId($callGraphID)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.graph` WHERE `callGraphId` = :callGraphID");
        $sql->bindParam(':callGraphID', $callGraphID);
        $result = self::executeSelectStatement($sql);
        return $result;
    }

    public static function selectAllFromGraph()
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.graph`");
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromObjectNodeByCallGraphId($callGraphId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE callGraphID = :callGraphID");
        $sql->bindParam(':callGraphID', $callGraphId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }
    public static function selectFromObjectNodeByCallGraphIdAndIsNotRef($callGraphId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE callGraphID = :callGraphID AND baseIdentifier!='REF'");
        $sql->bindParam(':callGraphID', $callGraphId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromObjectNodeByObjectID($objectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE objectId = :objectId");
        $sql->bindParam(':objectId', $objectId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromMessageByFromObjectID($fromObjectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `fromObjectId` = :fromObjectId");
        $sql->bindParam(':fromObjectId', $fromObjectId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromMessageByToObjectID($toObjectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `toObjectId` = :toObjectId");
        $sql->bindParam(':toObjectId', $toObjectId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromMessageByFromAndToObjectId($fromObjectId, $toObjectId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.message` WHERE `fromObjectId` = :fromObjectId AND `toObjectId` = :toObjectId");
        $sql->bindParam(':fromObjectId', $fromObjectId);
        $sql->bindParam(':toObjectId', $toObjectId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function selectFromObjectNodeByCallGraphIdWhereObjectIsRef($callGraphId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `callgraph.objectnode` WHERE callGraphID = :callGraphID AND baseIdentifier='REF'");
        $sql->bindParam(':callGraphID', $callGraphId);
        $result = self::executeSelectStatement($conn, $sql);
        return $result;
    }

    public static function deleteFromGraphByCallGraphId($callGraphId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("DELETE FROM `callgraph.graph` WHERE `callGraphId` = :callGraphId");
        $sql->bindParam(":callGraphId", $callGraphId);
        $result = self::executeDeleteStatement($conn, $sql);
        return $result;
    }

    public static function updateGraphSetCallGraphNameByCallGraphId($callGraphId, $callGraphName)
    {
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("UPDATE `callgraph.graph` SET callGraphName = :callGraphName WHERE callGraphId = :callGraphId");
        $sql->bindParam(":callGraphId", $callGraphId);
        $sql->bindParam(":callGraphName", $callGraphName);
        $result = self::executeUpdateStatement($conn, $sql);
        return $result;
    }
}

?>