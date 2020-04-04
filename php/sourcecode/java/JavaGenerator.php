<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . '/php/utilities/Constant.php';
require_once $root . "/php/utilities/Logger.php";
require_once $root . "/php/sourcecode/java/StubGenerator.php";
require_once $root . "/php/sourcecode/java/DriverGenerator.php";
use php\sourcecode\java;

class JavaGenerator
{

    private static $diagramId;

    private static $output;

    private static $stubObjectList;

    public static function generateStubs($diagramId, $stubList)
    {
        self::$diagramId = $diagramId;
        self::$output = array();
        self::convertStubListToObjectList($stubList);
        foreach ($stubList as $stub) {
            $isSuccess = self::generateStub($stub);
            if (! $isSuccess) {
                return self::$output;
            }
        }
        self::$output["isSuccess"] = "true";
        return self::$output;
    }

    private static function convertStubListToObjectList($stubList)
    {
        self::$stubObjectList = array();
        foreach ($stubList as $stub) {
            $objectNode = CallGraphService::selectFromObjectNodeByObjectID($stub["fromObjectId"])[0];
            array_push(self::$stubObjectList, $objectNode["baseIdentifier"]);
        }
    }

    private static function generateStub($stub)
    {
        $objectNode = CallGraphService::selectFromObjectNodeByObjectID($stub["toObjectId"])[0];
        $message = $stub; // Rename variable to prevent confusing.
        $classMethodList = self::getClassesAndMethod($objectNode["baseIdentifier"], $message);
        if ($classMethodList == false) {
            return false;
        }

        foreach ($classMethodList as $classMethod) {
            if (self::checkIfStubIsClassUnderTest($classMethod["class"]["className"])) {
                continue;
            }
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

    private static function checkIfStubIsClassUnderTest($class)
    {
        foreach (self::$stubObjectList as $stubObject) {
            if ($stubObject == $class) {
                return true;
            }
        }
        return false;
    }

    private static function getClassesAndMethod($baseIdentifier, $message)
    {
        $classMethodList = array();
        $messageName = $message["messageName"];
        if ($baseIdentifier == Constant::REF_DIAGRAM_TYPE) {
            $baseIdentifier = self::processReferenceDiagram($message);
        }
        $class = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $baseIdentifier);
        if (count($class) < 1) {
            self::handleError(Constant::NO_CLASS_FOUND_ERROR_MSG, $message);
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
           $concreteClasses = self::getConcreteChildClasses($class["classId"], $messageName, $classMethodList);
           $classMethodList = self::concatArray($classMethodList, $concreteClasses);
        }
        return $classMethodList;
    }

    private static function getConcreteChildClasses($parentClassId, $messageName, $classMethodList)
    {
        $childClassIdList = ClassDiagramService::selectChildIdFromInheritanceBySuperClassId($parentClassId);
        foreach ($childClassIdList as $childClassId) {
            $childClass = ClassDiagramService::selectFromClassByClassId($childClassId["childClassId"])[0];
            if ($childClass["InstanceType"] == Constant::CONCRETE_INSTANCE) {
                $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($childClassId["childClassId"], $messageName);
                array_push($classMethodList, array(
                    "class" => $childClass,
                    "methods" => $methods
                ));
            }else{
                $concreteClasses = self::getConcreteChildClasses($childClassId["childClassId"], $messageName, $classMethodList);
                $classMethodList = self::concatArray($classMethodList, $concreteClasses);
            }
        }         
        return $classMethodList;
    }
    private static function concatArray($sourceArray,$newArray){
        foreach($newArray as $item){
            array_push($sourceArray,$item);
        }
        return $sourceArray;
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
        $message = $driver; // Rename variable to prevent confusing.
        if ($fromObjectNode["baseIdentifier"] == Constant::ACTOR_TYPE) {
            return self::processActorObject($toObjectNode, $message);
        }
        $fromClassMethodList = self::getClassesAndMethod($fromObjectNode["baseIdentifier"], $message);
        if ($fromClassMethodList == false) {
            return false;
        }

        $toClassMethodList = self::getClassesAndMethod($toObjectNode["baseIdentifier"], $message);
        if ($toClassMethodList == false) {
            return false;
        }
        foreach ($fromClassMethodList as $fromClassMethod) {
            $filename = $fromClassMethod["class"]["className"] . "Driver.java";
            foreach ($toClassMethodList as $toClassMethod) {
                $file = SourceCodeService::selectFromSourceCodeByFilename($filename);
                if (count($file) == 0) {
                    $fileId = java\DriverGenerator::createNewFile($message["messageId"], $filename, $fromClassMethod["class"], $toClassMethod["class"], $toClassMethod["methods"]);
                    if ($fileId != - 1) {
                        self::$output[$fileId] = $filename;
                    } else {
                        self::handleError(Constant::CODE_GENERATION_ERROR_MSG, "");
                        return false;
                    }
                } else {
                    $fileId = java\DriverGenerator::addToExistFile($message["messageId"], $file[0], $toClassMethod["class"], $toClassMethod["methods"]);
                    self::$output[$fileId] = $filename;
                }
            }
        }

        return true;
    }

    private static function processActorObject($toObjectNode, $message)
    {
        $toClassMethodList = self::getClassesAndMethod($toObjectNode["baseIdentifier"], $message);
        if ($toClassMethodList == false) {
            return false;
        }
        $filename = "ActorDriver.java";
        foreach ($toClassMethodList as $toClassMethod) {
            $file = SourceCodeService::selectFromSourceCodeByFilename($filename);
            if (count($file) == 0) {
                $fileId = java\DriverGenerator::createNewFile($message["messageId"], $filename, Constant::ACTOR_TYPE, $toClassMethod["class"], $toClassMethod["methods"]);
                if ($fileId != - 1) {
                    self::$output[$fileId] = $filename;
                } else {
                    self::handleError(Constant::CODE_GENERATION_ERROR_MSG, "");
                    return false;
                }
            } else {
                $fileId = java\DriverGenerator::addToExistFile($message["messageId"], $file[0], $toClassMethod["class"], $toClassMethod["methods"]);
                self::$output[$fileId] = $filename;
            }
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

    private static function processReferenceDiagram($message)
    {
        $destinationGraphId = CallGraphService::selectFromReferenceDiagramByObjectId($message["toObjectId"])[0]["destinationId"];
        if (! isset($destinationGraphId) || $destinationGraphId == "") {
            self::handleError(Constant::NO_REFERENCE_DIAGRAM_ERROR_MSG, $message);
            return false;
        }
        $rootObjects = self::getRootObjectInCallGraph($destinationGraphId);
        if (count($rootObjects) == 0) {
            self::handleError(Constant::REF_DIAGRAM_MISFORMAT_ERROR_MSG, $destinationGraphId);
            return false;
        } else if (count($rootObjects) == 1) {
            return $rootObjects[0];
        } else {
            return self::justifyLinkedObject($rootObjects, $message);
        }
    }

    private static function getRootObjectInCallGraph($graphId)
    {
        $rootObjects = array();
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphId($graphId);
        foreach ($objectList as $objectNode) {
            $objectId = $objectNode["objectId"];
            $baseIdentifier = $objectNode["baseIdentifier"];
            $inMessages = CallGraphService::selectFromMessageByToObjectIDAndMessageTypeNonRecursive($objectId, Constant::CALLING_MESSAGE_TYPE);
            if (count($inMessages) == 0 && $baseIdentifier != Constant::ACTOR_TYPE) {
                array_push($rootObjects, $baseIdentifier);
            }
        }
        return $rootObjects;
    }

    private static function justifyLinkedObject($rootObjects, $message)
    {
        $messageName = $message["messageName"];
        foreach ($rootObjects as $rootObject) {
            $class = ClassDiagramService::selectClassByDiagramIdAndObjectBase(self::$diagramId, $rootObject)[0];
            if ($message["messageType"] == Constant::CREATE_MESSAGE_TYPE) {
                $messageName = $class["className"];
            }
            $methods = ClassDiagramService::selectMethodByClassIdAndMessageName($class["classId"], $messageName);
            if (count($methods) > 0) {
                return $rootObject;
            }
        }
        return false;
    }
}
?>