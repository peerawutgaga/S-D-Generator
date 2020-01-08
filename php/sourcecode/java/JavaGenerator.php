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
        $classMethodList = self::getClassesAndMethod($objectNode["baseIdentifier"], $message);
        if ($classMethodList == false) {
            return false;
        }
        foreach ($classMethodList as $classMethod) {
            $filename = $classMethod["class"]["className"] . "Stub.java";
            $file = SourceCodeService::selectFromSourceCodeByFilename($filename);
            if (count($file) == 0) {
                $fileId = java\StubGenerator::createNewFile($filename, $classMethod["class"], $classMethod["methods"]);
                if ($fileId != - 1) {
                    self::$output[$fileId] = $filename;
                } else {
                    self::handleError(Constant::CODE_GENERATION_ERROR_MSG, "");
                    return false;
                }
            } else {
                $fileId = java\StubGenerator::addToExistFile($file[0], $classMethod["methods"]);
                self::$output[$fileId] = $filename;
            }
        }
        return true;
    }

    private static function getClassesAndMethod($baseIdentifier, $message)
    {
        $classMethodList = array();
        $messageName = $message["messageName"];
        $class = ClassDiagramService::selectClassByDiagramIdAndObjectBase(8, $baseIdentifier);
        if (count($class) < 1) {
            self::handleError(Constant::NO_CLASS_FOUND_ERROR_MSG, $baseIdentifier);
            return false;
        } else if (count($class) > 1) {
            self::handleError(Constant::CLASS_NOT_UNIQUE_ERROR_MSG, $class);
            return false;
        }
        $class = $class[0]; // Make single item
        if ($message["messageType"] == Constant::CREATE_MESSAGE_TYPE) {
            $messageName = $class["className"];
        }
        if ($class["InstanceType"] == Constant::CONCRETE_INSTANCE) {
            $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($class["classId"], $messageName);
            array_push($classMethodList, array(
                "class" => $class,
                "methods" => $methods
            ));
        } else {
            $childClassIdList = ClassDiagramService::selectChildIdFromInheritanceBySuperClassId($class["classId"]);
            foreach ($childClassIdList as $childClassId) {
                if ($messageName == "create") {
                    $messageName = $class["className"];
                }
                $childClass = ClassDiagramService::selectFromClassByClassId($childClassId["childClassId"]);
                $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($childClassId["childClassId"], $messageName);
                array_push($classMethodList, array(
                    "class" => $childClass[0],
                    "methods" => $methods
                ));
            }
        }
        return $classMethodList;
    }

    public static function generateDrivers($diagramId, $driverList)
    {
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

    private static function generateDriver($driver)
    {
        $fromObjectNode = CallGraphService::selectFromObjectNodeByObjectID($driver["fromObjectId"])[0];
        $toObjectNode = CallGraphService::selectFromObjectNodeByObjectID($driver["toObjectId"])[0];
        $message = $driver;
        $fromClass = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $fromObjectNode["baseIdentifier"]);
        $toClass = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $toObjectNode["baseIdentifier"]);
        if (count($fromClass) < 1 || count($toClass) < 1) {
            self::handleError(Constant::NO_CLASS_FOUND_ERROR_MSG, $fromObjectNode);
            self::handleError(Constant::NO_CLASS_FOUND_ERROR_MSG, $toObjectNode);
            return false;
        } else if (count($fromClass) > 1 || count($toClass) > 1) {
            self::handleError(Constant::CLASS_NOT_UNIQUE_ERROR_MSG, $fromClass);
            self::handleError(Constant::CLASS_NOT_UNIQUE_ERROR_MSG, $toClass);
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
            $fileId = java\DriverGenerator::createNewFile($filename, $fromClass, $toClass, $methods);
            if ($fileId != - 1) {
                self::$output[$fileId] = $filename;
            } else {
                self::handleError(Constant::CODE_GENERATION_ERROR_MSG, "");
                return false;
            }
        } else {
            $fileId = java\DriverGenerator::addToExistFile($file[0], $fromClass, $toClass, $methods);
            self::$output[$fileId] = $filename;
        }
        return true;
    }

    private static function handleError($errorMessage, $errorPayload)
    {
        Logger::logInternalError("JavaGenerator", $errorMessage . " - " . print_r($errorPayload, true));
        self::$output = array();
        self::$output["isSuccess"] = "false";
        self::$output["errorMessage"] = $errorMessage;
    }
}
?>