<?php
namespace php\sourcecode\java;

use ClassDiagramService;
use Constant;
use DataGenerator;
// use Script;
use SourceCodeService;
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/utilities/Constant.php";
require_once $root . "/php/utilities/DataGenerator.php";
require_once $root . "/php/sourcecode/common/GuardConditionProcessor.php";

class DriverGenerator
{

    private static $content;

    private static $declaredVariableList;

    private static $messageId;

    public static function createNewFile($messageId, $filename, $fromClass, $toClass, $methods)
    {
        self::$content = ""; // Reset file content
        self::$messageId = $messageId;
        self::$declaredVariableList = array();
        if ($fromClass == \Constant::ACTOR_TYPE) {
            self::createActorDriver($filename,$toClass,$methods);
        } else {
            self::declarePackage($fromClass);
            self::declareImports($toClass);
            self::declareClassHeader($fromClass);
            self::generateMethods($toClass, $methods);
            self::closeClass();
        }
        return SourceCodeService::insertIntoSourceCodeFile($filename, self::$content, Constant::JAVA_LANG, Constant::DRIVER_TYPE);
    }

    public static function addToExistFile($messageId, $file, $toClass, $methods)
    {
        self::$content = $file["filePayload"];
        self::$content = rtrim(self::$content, "}");
        self::$messageId = $messageId;
        self::generateMethods($toClass, $methods);
        self::closeClass();
        SourceCodeService::updateSourceCodeFileSetFilePayloadByFileId(self::$content, $file["fileId"]);
        return $file["fileId"];
    }

    private static function declarePackage($class)
    {
        self::$content .= "package driver." . $class["namespace"] . ";\r\n";
    }

    private static function declareImports($class)
    {
        if (! strpos(self::$content, "/*--- AUTO IMPORT START HERE ---*/")) {
            self::createNewImportBlock();
        }
        self::addToExistedImportBlock($class);
    }

    private static function createNewImportBlock()
    {
        self::$content .= "/*--- AUTO IMPORT START HERE ---*/\r\n";
        self::$content .= "/*--- AUTO IMPORT END HERE ---*/\r\n";
    }

    private static function addToExistedImportBlock($class)
    {
        $namespace = $class["namespace"];
        $className = $class["className"];
        $importStatement = "import " . $namespace . "." . $className . ";\r\n/*--- AUTO IMPORT END HERE ---*/";
        self::$content = str_replace("/*--- AUTO IMPORT END HERE ---*/", $importStatement, self::$content);
    }

    private static function getExistedMethodList($methods, $className)
    {
        $existedMethodList = array();
        foreach ($methods as $method) {
            $methodName = self::convertToTestMethodName($method["methodName"], $className);
            if (strpos(self::$content, $methodName . "(")) {
                array_push($existedMethodList, $methodName);
            }
        }
        return $existedMethodList;
    }

    private static function declareClassHeader($class)
    {
        self::$content .= "class " . $class["className"] . "{\r\n";
    }

    private static function generateMethods($toClass, $methods)
    {
        $className = $toClass["className"];
        $existedMethodList = self::getExistedMethodList($methods, $className);
        foreach ($methods as $method) {
            $visibility = $method["visibility"];
            $instanceType = $method["instanceType"];
            $returnType = $method["returnType"];
            $isConstructor = $method["isConstructor"];
            $methodName = self::convertToTestMethodName($method["methodName"], $className);
            if ($visibility == "private") {
                continue;
            }
            if (in_array($methodName, $existedMethodList)) {
                continue;
            }
            self::declareMethodHeader($method, $className);
            if ($isConstructor) {
                self::generateConstructorInvocation($method);
                self::closeMethod();
                continue;
            }
            if ($instanceType != Constant::STATIC_INSTANCE) {
                self::declareInstance($className);
            }
            self::generateInvocation($className, $method);
            if ($returnType != \Constant::VOID_TYPE) {
                self::generateAssertion($returnType);
            }
            self::closeMethod();
        }
    }

    private static function generateConstructorInvocation($method)
    {
        $inputValues = self::getInputValue($method["methodId"]);
        $methodName = $method["methodName"];
        self::$content .= "\t\tnew " . $methodName . "(" . $inputValues . ");\r\n";
    }

    private static function convertToTestMethodName($methodName, $className)
    {
        $methodName = ucfirst($methodName); // Uppercase first character
        return "test" . $methodName . "In" . $className;
    }

    private static function declareMethodHeader($method, $className)
    {
        $methodName = self::convertToTestMethodName($method["methodName"], $className);
        if (isset(self::$declaredVariableList[$methodName])) {
            self::$declaredVariableList[$methodName] += 1;
            $methodName .= self::$declaredVariableList[$methodName];
        } else {
            self::$declaredVariableList[$methodName] = 0;
        }
        self::$content .= "\t@Test\r\n\tpublic void " . $methodName . "(){\r\n";
    }

    private static function declareInstance($className)
    {
        $variableName = lcfirst($className);
        self::$content .= "\t\t" . $className . " " . $variableName . " = new " . $className . "();\r\n";
    }

    private static function generateInvocation($className, $method)
    {
        $methodId = $method["methodId"];
        $methodName = $method["methodName"];
        $instanceType = $method["instanceType"];
        $returnType = $method["returnType"];
        $inputValues = self::getInputValue($methodId);
        self::$content .= "\t\t";
        if ($returnType == \Constant::STRING_TYPE) {
            $returnType = "String"; // Upper first character case to match Java string declaration
        }
        if ($returnType != \Constant::VOID_TYPE) {
            $variableName = "actualResult";
            self::$content .= $returnType . " " . $variableName . " = ";
        }
        if ($instanceType == Constant::STATIC_INSTANCE) {
            self::$content .= $className . "." . $methodName . "(" . $inputValues . ");\r\n";
        } else {
            self::$content .= lcfirst($className) . "." . $methodName . "(" . $inputValues . ");\r\n";
        }
    }

    private static function generateAssertion($returnType)
    {
        $expectedResult = \DataGenerator::getRandomData($returnType);
        if ($returnType == "double" || $returnType == "float") {
            $precision = \DataGenerator::getRandomDoubleWithBound(0, 1, 3);
            self::$content .= "\t\tassertEquals(" . $expectedResult . ",actualResult," . $precision . ");\r\n";
        } else {
            self::$content .= "\t\tassertEquals(" . $expectedResult . ",actualResult);\r\n";
        }
    }

    private static function getInputValue($methodId)
    {
        $params = ClassDiagramService::selectParamByMethodId($methodId);
        if (count($params) == 0) {
            return "";
        }
        $params = DataGenerator::sortBySequenceIndex($params);
        $guardCondition = \CallGraphService::selectFromGuardConditionByMessageId(self::$messageId)[0];
        $inputValues = self::generateInputParamValue($params, $guardCondition["statement"]);
        return $inputValues;
    }

    private static function generateInputParamValue($paramList, $guardConditionStr)
    {
        $paramListStr = "";
        $guardCondition = \GuardConditionProcessor::parseGuardConditionString($guardConditionStr);
        foreach ($paramList as $param) {
            $dataType = $param["dataType"];
            $typeModifier = $param["typeModifier"];
            if (empty($typeModifier)) {

                if ($guardCondition["variable"] == $param["paramName"]) {
                    $inputValue = \GuardConditionProcessor::getValueByCondition($param, $guardCondition);
                } else {
                    $inputValue = DataGenerator::getRandomData($dataType);
                }
            } else {
                $inputValue = "null";
            }
            $paramListStr .= $inputValue . ",";
        }
        $paramListStr = \DataGenerator::removeLastComma($paramListStr);
        return $paramListStr;
    }

    private static function closeMethod()
    {
        self::$content .= "\t}\r\n";
    }

    private static function closeClass()
    {
        self::$content .= "}";
    }

    private static function createActorDriver($filename,$toClass,$methods)
    {
        self::$content .= "package driver.actor;\r\n";//Declare package header
        self::declareImports($toClass);
        self::$content .= "class Actor{\r\n";//Declare class header
        self::generateMethods($toClass, $methods);
        self::closeClass();
    }
}

