<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/php/sourcecode/java/JavaGenerator.php';
require_once $root . '/php/database/CallGraphService.php';
require_once $root . '/php/database/ClassDiagramService.php';
require_once $root . '/php/utilities/Constant.php';
require_once $root . '/php/utilities/Common.php';

if (isset($_POST['callGraphId']) && isset($_POST['diagramId']) && isset($_POST['objectList']) && isset($_POST['sourceLang'])) {
    $callGraphId = $_POST['callGraphId'];
    $diagramId = $_POST['diagramId'];
    $objectList = $_POST['objectList'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createCode($callGraphId, $diagramId, $objectList, $sourceLang);
}

class SourceCodeGenerator
{

    private static $diagramId;

    private static $callGraphId;

    private static $objectList;

    public static function createCode($callGraphId, $diagramId, $objectList, $sourceLang)
    {
        self::$diagramId = $diagramId;
        self::$callGraphId = $callGraphId;
        self::$objectList = array();
        $stubList = self::identifyStub($objectList);
        $driverList = self::identifyDriver($objectList);
        $output = array();
        if ($sourceLang == Constant::JAVA_LANG) {
            $stubFile = JavaGenerator::generateStubs($diagramId, $stubList);
            $driverFile = JavaGenerator::generateDrivers($diagramId, $driverList);
            if ($stubFile["isSuccess"] != "true") {
                $output = $stubFile;
            } else if ($driverFile["isSuccess"] != "true") {
                $output = $driverFile;
            } else {
                $output = $stubFile + $driverFile;
            }
            echo json_encode($output);
        }
    }

    private static function identifyStub($objectListStr)
    {
        $objectList = explode(",", $objectListStr);
        self::$objectList = Common::concatArray(self::$objectList, $objectList);
        $stubList = array();
        foreach ($objectList as $objectId) {
            $callingMessageList = self::getCallingMessageList($objectId);
            foreach ($callingMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["toObjectId"] && ! in_array($sentMessage["toObjectId"], self::$objectList)) {
                    array_push($stubList, $sentMessage);
                }
            }
        }
        return $stubList;
    }

    private static function identifyDriver($objectListStr)
    {
        $objectList = explode(",", $objectListStr);
        self::$objectList = Common::concatArray(self::$objectList, $objectList);
        $driverList = array();        
        foreach ($objectList as $objectId) {
            $calledMessageList = self::getCalledMessageList($objectId);
            foreach ($calledMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["fromObjectId"] && ! in_array($sentMessage["fromObjectId"], self::$objectList)) {
                    array_push($driverList, $sentMessage);
                }
            }
        }
        return $driverList;
    }

    private static function getCallingMessageList($objectId)
    {
        $callingMessageList = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
        $createMessageList = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($objectId, Constant::CREATE_MESSAGE_TYPE);
        $otherObjects = self::getOtherObjectInCallGraph($objectId);
        if (count($otherObjects) > 0) {
            $otherMessage = array();
            foreach ($otherObjects as $otherObject) {
                array_push(self::$objectList, $otherObject["objectId"]);
                $otherCallingMessage = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($otherObject["objectId"], Constant::CALLING_MESSAGE_TYPE);
                $otherMessage = Common::concatArray($otherMessage, $otherCallingMessage);
                $otherCreateMessage = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($otherObject["objectId"], Constant::CREATE_MESSAGE_TYPE);
                $otherMessage = Common::concatArray($otherMessage, $otherCreateMessage);
            }
        }
        $allMessageList = array();
        if (count($callingMessageList) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $callingMessageList);
        }
        if (count($createMessageList) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $createMessageList);
        }
        if (count($otherMessage) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $otherMessage);
        }
        return $allMessageList;
    }

    private static function getCalledMessageList($objectId)
    {
        $callingMessageList = CallGraphService::selectFromMessageByToObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
        $createMessageList = CallGraphService::selectFromMessageByToObjectIDAndMessageType($objectId, Constant::CREATE_MESSAGE_TYPE);
        $otherObjects = self::getOtherObjectInCallGraph($objectId);
        if (count($otherObjects) > 0) {
            $otherMessage = array();
            foreach ($otherObjects as $otherObject) {
                array_push(self::$objectList, $otherObject["objectId"]);
                $otherCallingMessage = CallGraphService::selectFromMessageByToObjectIDAndMessageType($otherObject["objectId"], Constant::CALLING_MESSAGE_TYPE);
                $otherMessage = Common::concatArray($otherMessage, $otherCallingMessage);
                $otherCreateMessage = CallGraphService::selectFromMessageByToObjectIDAndMessageType($otherObject["objectId"], Constant::CREATE_MESSAGE_TYPE);
                $otherMessage = Common::concatArray($otherMessage, $otherCreateMessage);
            }
        }
        $allMessageList = array();
        if (count($callingMessageList) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $callingMessageList);
        }
        if (count($createMessageList) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $createMessageList);
        }
        if (count($otherMessage) > 0) {
            $allMessageList = Common::concatArray($allMessageList, $otherMessage);
        }
        return $allMessageList;
    }

    private static function getOtherObjectInCallGraph($objectId)
    {
        $baseIdentifier = CallGraphService::selectFromObjectNodeByObjectID($objectId)[0]["baseIdentifier"];
        $otherObject = CallGraphService::selectOtherObjectNodeInCallGraphByBaseIdentifier(self::$callGraphId, $objectId, $baseIdentifier);
        return $otherObject;
    }
}
?>