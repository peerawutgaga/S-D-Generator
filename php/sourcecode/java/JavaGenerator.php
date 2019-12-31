<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . '/php/utilities/Script.php';
require_once $root . '/php/utilities/Constant.php';
require_once $root . "/php/utilities/Logger.php";
require_once $root . "/php/sourcecode/java/StubGenerator.php";

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
        $objectNode = CallGraphService::selectFromObjectNodeByObjectID($stub["objectId"])[0];
        $message = $stub["message"];

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
}
?>