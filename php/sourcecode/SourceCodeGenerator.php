<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/php/sourcecode/java/JavaGenerator.php';
require_once $root . '/php/database/CallGraphService.php';
require_once $root . '/php/database/ClassDiagramService.php';
require_once $root . '/php/utilities/Constant.php';

if (isset($_POST['diagramId']) && isset($_POST['objectList']) && isset($_POST['sourceLang'])) {
    $diagramId = $_POST['diagramId'];
    $objectList = $_POST['objectList'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createCode($diagramId, $objectList, $sourceLang);
}

class SourceCodeGenerator
{

    private static $diagramId;

    public static function createCode($diagramId, $objectList, $sourceLang)
    {
        self::$diagramId = $diagramId;
        $stubList = self::identifyStub($objectList);
        $driverList = self::identifyDriver($objectList);
        $output = array();
        if ($sourceLang == Constant::JAVA_LANG) {
            $stubFile = JavaGenerator::generateStubs($diagramId, $stubList);               
            $driverFile = JavaGenerator::generateDrivers($diagramId, $driverList);
            if($stubFile["isSuccess"]!="true"){
                $output = $stubFile;
            }else if($driverFile["isSuccess"]!="true"){
                $output = $driverFile;
            }else{
                $output=$stubFile + $driverFile;               
            }
            echo json_encode($output);
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
                if ($objectId != $sentMessage["toObjectId"] && ! in_array($sentMessage["toObjectId"], $objectList)) {
                    array_push($stubList, $sentMessage);
                }
            }
            foreach ($createMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["toObjectId"] && ! in_array($sentMessage["toObjectId"], $objectList)) {
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
                if ($objectId != $sentMessage["fromObjectId"] && ! in_array($sentMessage["fromObjectId"], $objectList)) {
                    array_push($driverList, $sentMessage);
                }
            }
            foreach ($createMessageList as $sentMessage) {
                // Check if message is not self calling message
                if ($objectId != $sentMessage["fromObjectId"] && ! in_array($sentMessage["fromObjectId"], $objectList)) {
                    array_push($driverList, $sentMessage);
                }
            }
        }
        return $driverList;
    }
}
?>