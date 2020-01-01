<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . '/php/utilities/Script.php';
require_once $root . '/php/utilities/Constant.php';
require_once $root . "/php/utilities/Logger.php";
require_once $root . "/php/sourcecode/java/StubGenerator.php";
require_once $root . "/php/sourcecode/java/DriverGenerator.php";
use php\sourcecode\java;

class JavaGenerator
{

    private static $diagramId;

    private static $output;

    public static function generateStubs($diagramId, $stubList)
    {
        
        self::$diagramId = $diagramId;
        self::$output = array();
        foreach ($stubList as $stub) {
            $isSuccess = self::generateStub($stub);
            if (! $isSuccess) {
                return self::$output;
            }
        }
        self::$output["isSuccess"] = "true";
        return self::$output;
    }

    private static function generateStub($stub)
    {
        $objectNode = CallGraphService::selectFromObjectNodeByObjectID($stub["toObjectId"])[0];
        $message = $stub;
        $class = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $objectNode["baseIdentifier"]);
        if (count($class) < 1) {
            Logger::logInternalError("JavaGenerator", Constant::NO_CLASS_FOUND_ERROR_MSG . " - " . print_r($objectNode, true));
            self::$output = array();
            self::$output["isSuccess"] = "false";
            self::$output["errorMessage"] = Constant::NO_CLASS_FOUND_ERROR_MSG;
            return false;
        } else if (count($class) > 1) {
            Logger::logInternalError("JavaGenerator", Constant::CLASS_NOT_UNIQUE_ERROR_MSG . " - " . print_r($class, true));
            self::$output = array();
            self::$output["isSuccess"] = "false";
            self::$output["errorMessage"] = Constant::CLASS_NOT_UNIQUE_ERROR_MSG;

            return false;
        }
        $class = $class[0]; // Make a single class from array
                            // Replace create message name with class name to call instructor
        if ($message["messageType"] == Constant::CREATE_MESSAGE_TYPE) {
            $message["messageName"] = $class["className"];
        }
        $filename = $class["className"] . "Stub.java";
        $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($class["classId"], $message["messageName"]);
        $file = SourceCodeService::selectFromSourceCodeByFilename($filename);
        if (count($file) == 0) {
            java\StubGenerator::createNewFile($filename, $class, $methods);
        } else {
            java\StubGenerator::addToExistFile($file[0], $methods);
        }
        return true;
    }
    public static function generateDrivers($diagramId, $driverList){
        self::$diagramId = $diagramId;
        self::$output = array();
        foreach ($driverList as $driver) {
            $isSuccess = self::generateDriver($driver);
            if (! $isSuccess) {
                return self::$output;
            }
        }
        self::$output["isSuccess"] = "true";
        return self::$output;
    }
    private static function generateDriver($driver){
        $fromObjectNode = CallGraphService::selectFromObjectNodeByObjectID($driver["fromObjectId"])[0];
        $toObjectNode = CallGraphService::selectFromObjectNodeByObjectID($driver["toObjectId"])[0];
        $message = $driver;  
        $fromClass = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $fromObjectNode["baseIdentifier"]);
        $toClass = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $toObjectNode["baseIdentifier"]);
        if (count($fromClass) < 1||count($toClass)<1) {
            Logger::logInternalError("JavaGenerator", Constant::NO_CLASS_FOUND_ERROR_MSG . " - " . print_r($fromObjectNode, true). print_r($toObjectNode, true));
            self::$output = array();
            self::$output["isSuccess"] = "false";
            self::$output["errorMessage"] = Constant::NO_CLASS_FOUND_ERROR_MSG;
            return false;
        } else if (count($fromClass) > 1||count($toClass) > 1) {
            Logger::logInternalError("JavaGenerator", Constant::CLASS_NOT_UNIQUE_ERROR_MSG . " - " . print_r($fromClass, true). print_r($toClass, true));
            self::$output = array();
            self::$output["isSuccess"] = "false";
            self::$output["errorMessage"] = Constant::CLASS_NOT_UNIQUE_ERROR_MSG;
            return false;
        }
        $fromClass = $fromClass[0]; // Make a single class from array
        $toClass = $toClass[0]; // Make a single class from array
        // Replace create message name with class name to call instructor
        if ($message["messageType"] == Constant::CREATE_MESSAGE_TYPE) {
            $message["messageName"] = $toClass["className"];
        }
        $filename = $fromClass["className"] . "Driver.java";
        $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($toClass["classId"], $message["messageName"]);
        $file = SourceCodeService::selectFromSourceCodeByFilename($filename);
        if (count($file) == 0) {
            java\DriverGenerator::createNewFile($filename, $fromClass,$toClass, $methods);
        } else {
            java\DriverGenerator::addToExistFile($file[0], $fromClass,$toClass,$methods);
        }
        return true;
    }
}
?>