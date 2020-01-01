<?php
namespace php\sourcecode\java;

use ClassDiagramService;
use Constant;
use DataGenerator;
//use Script;
use SourceCodeService;
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/utilities/Constant.php";
require_once $root . "/php/utilities/DataGenerator.php";
//require_once $root . "/php/utilities/Script.php";

class DriverGenerator
{

    private static $content;

    private static $declaredVariableList;

    public static function createNewFile($filename, $fromClass, $toClass, $methods)
    {
        self::$content = ""; // Reset file content
        self::$declaredVariableList = array();
        self::declarePackage($fromClass);
        self::declareImports($toClass);
        self::declareClassHeader($fromClass);
        self::generateMethods($toClass, $methods, false);
        self::closeClass();
        SourceCodeService::insertIntoSourceCodeFile($filename, self::$content, Constant::JAVA_LANG, Constant::DRIVER_TYPE);
    }

    public static function addToExistFile($file, $fromClass, $toClass, $methods)
    {
        self::$content = $file["filePayload"];
        self::$content = rtrim(self::$content, "}");
        self::generateMethods($toClass, $methods, true);
        self::closeClass();
        SourceCodeService::updateSourceCodeFileSetFilePayloadByFileId(self::$content, $file["fileId"]);
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

    private static function declareClassHeader($class)
    {
        self::$content .= "class " . $class["className"] . "{\r\n";
    }

    private static function generateMethods($toClass, $methods, $skipExistedMethod)
    {
        foreach ($methods as $method) {
            $visibility = $method["visibility"];
            $instanceType = $method["instanceType"];
            $returnType = $method["returnType"];
            $className = $toClass["className"];
            $isConstructor = $method["isConstructor"];
            if ($visibility == "private") {
                continue;
            }
            if ($skipExistedMethod) {
                $methodName = self::convertToTestMethodName($method["methodName"]);
                if (strpos(self::$content, $methodName . "(")) {
                    continue;
                }
            }
            self::declareMethodHeader($method);
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
        $methodId = $method["methodId"];
        $params = ClassDiagramService::selectParamByMethodId($methodId);
        $params = DataGenerator::sortBySequenceIndex($params);
        $inputValues = self::generateInputParamValue($params);
        $methodName = $method["methodName"];
        self::$content .= "\t\tnew " . $methodName . "(" . $inputValues . ");\r\n";
    }

    private static function convertToTestMethodName($methodName)
    {
        $methodName = ucfirst($methodName); // Uppercase first character
        return "test" . $methodName;
    }

    private static function declareMethodHeader($method)
    {
        $methodName = self::convertToTestMethodName($method["methodName"]);
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
        $params = ClassDiagramService::selectParamByMethodId($methodId);
        $params = DataGenerator::sortBySequenceIndex($params);
        $inputValues = self::generateInputParamValue($params);
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

    private static function generateInputParamValue($paramList)
    {
        $paramListStr = "";
        foreach ($paramList as $param) {
            $dataType = $param["dataType"];
            $typeModifier = $param["typeModifier"];
            if (empty($typeModifier)) {
                $inputValue = DataGenerator::getRandomData($dataType);
            } else {
                $inputValue = "null";
            }
            $paramListStr .= $inputValue . ",";
        }
        $lastCharacter = substr($paramListStr, - 1);
        if ($lastCharacter == ",") {
            $paramListStr = substr($paramListStr, 0, - 1);
        }
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
}

