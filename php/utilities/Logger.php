<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/Database.php";
require_once $root . "/php/utilities/Constant.php";

class Logger
{

    private static function insertIntoEvent($eventName, $eventType, $eventProducer, $eventPayload)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `logging.event` (`eventName`, `eventType`,`eventProducer`,`eventPayload`) 
        VALUES(:eventName,:eventType,:eventProducer,:eventPayload)");
        $sql->bindParam(":eventName", $eventName);
        $sql->bindParam(":eventType", $eventType);
        $sql->bindParam(":eventProducer", $eventProducer);
        $sql->bindParam(":eventPayload", $eventPayload);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Script::consoleLog($e->getMessage());
        } finally{
            unset($conn);
        }
    }
    public static function logWarning($eventProducer, $eventPayload){
        self::insertIntoEvent("WARNING", Constant::WARNING_CODE, $eventProducer, $eventPayload);
    }
    public static function logDatabaseError($eventProducer, $eventPayload)
    {
        self::insertIntoEvent("DATABASE_ERROR", Constant::ERROR_CODE, $eventProducer, $eventPayload);
    }

    public static function logInternalError($eventProducer, $eventPayload)
    {
        self::insertIntoEvent("INTERNAL_ERROR", Constant::ERROR_CODE, $eventProducer, $eventPayload);
    }
}

