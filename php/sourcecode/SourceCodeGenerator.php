<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/php/sourcecode/java/JavaGenerator.php';
require_once $root . '/php/database/CallGraphService.php';
require_once $root . '/php/utilities/Constant.php';
require_once $root . '/php/utilities/Script.php';

if (isset($_POST['diagramId']) && isset($_POST['objectList']) && isset($_POST['sourceType']) && isset($_POST['sourceLang'])) {
    $diagramId = $_POST['diagramId'];
    $objectList = $_POST['objectList'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createCode($diagramId, $objectList, $sourceType, $sourceLang);
}

class SourceCodeGenerator
{

    private static $diagramId;

    private static $objectList;

    public static function createCode($diagramId, $objectList, $sourceType, $sourceLang)
    {
        self::$diagramId = $diagramId;
        self::$objectList = $objectList;
        if ($sourceType == Constant::STUB_TYPE) {
            $stubList = self::identifyStub($objectList);
            if ($sourceLang == Constant::JAVA_LANG) {
                
                $output = JavaGenerator::generateStubs($diagramId, $stubList);
                echo json_encode($output);
            } 
        } else if ($sourceType == Constant::DRIVER_TYPE) {
            $driverList = self::identifyDriver($objectList);
            if ($sourceLang == Constant::JAVA_LANG) {
                $output = JavaGenerator::generateDrivers($diagramId, $driverList);
                echo json_encode($output);
            }
        }
    }

    private static function identifyStub($objectListStr)
    {
        $objectList = explode(",", $objectListStr);
        $stubList = array();
        foreach ($objectList as $objectId) {
            $callingMessageList = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
            $createMessageList = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($objectId, Constant::CREATE_MESSAGE_TYPE);
            foreach ($callingMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["toObjectId"]&&!in_array($sentMessage["toObjectId"],$objectList )) {
                    array_push($stubList, $sentMessage);
                }
            }
            foreach ($createMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["toObjectId"]&&!in_array($sentMessage["toObjectId"],$objectList)) {
                    array_push($stubList, $sentMessage);
                }
            }
        }
        return $stubList;
    }

    private static function identifyDriver($objectListStr)
    {
        $objectList = explode(",", $objectListStr);
        $driverList = array();
        foreach ($objectList as $objectId) {
            $callingMessageList = CallGraphService::selectFromMessageByToObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
            $createMessageList = CallGraphService::selectFromMessageByToObjectIDAndMessageType($objectId, Constant::CREATE_MESSAGE_TYPE);
            foreach ($callingMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["fromObjectId"]&&!in_array($sentMessage["fromObjectId"],$objectList)) {
                    array_push($driverList, $sentMessage);
                }
            }
            foreach ($createMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["fromObjectId"]&&!in_array($sentMessage["fromObjectId"],$objectList)) {
                    array_push($driverList, $sentMessage);
                }
            }
        }
        return $driverList;
    }
}
?>