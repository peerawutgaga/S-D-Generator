<?php
namespace php\sourcecode\java;

use ClassDiagramService;
use Constant;
use DataGenerator;
use Script;
use SourceCodeService;
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/database/SourceCodeService.php";
require_once $root . "/php/utilities/Constant.php";
require_once $root . "/php/utilities/DataGenerator.php";
require_once $root . "/php/utilities/Script.php";

class StubGenerator
{

    private static $content;

    public static function createNewFile($filename, $class, $methods)
    {
        self::$content = ""; // Reset file content
        self::declarePackage($class);
        self::declareClassHeader($class);
        self::generateMethods($methods);
        self::closeClass();
        SourceCodeService::insertIntoSourceCodeFile($filename, self::$content, Constant::JAVA_LANG, Constant::STUB_TYPE);
        echo self::$content;
    }

    private static function declarePackage($class)
    {
        self::$content .= "package stub." . $class["namespace"] . ";\r\n";
    }

    private static function declareClassHeader($class)
    {
        self::$content .= "class " . $class["className"] . "{\r\n";
    }

    private static function generateMethods($methods)
    {
        foreach ($methods as $method) {
            $visibility = $method["visibility"];
            $isConstructor = $method["isConstructor"];
            if ($visibility == "private") {
                continue;
            }
            if ($isConstructor) {
                self::generateConstructor($method);
                continue;
            }
            self::declareMethodHeader($method);
        }
    }

    private static function generateConstructor($method)
    {
        $methodId = $method["methodId"];
        $visibility = $method["visibility"];
        $methodName = $method["methodName"];
        $params = ClassDiagramService::selectParamByMethodId($methodId);
        $params = DataGenerator::sortBySequenceIndex($params);
        $paramList = self::generateParamList($params);
        self::$content .= "\t" . $visibility . " " . $methodName . "(" . $paramList . "){\r\n";
        if (count($params) > 0) {
            self::generatePrintLine($params);
        }
        self::closeMethod();
    }

    private static function declareMethodHeader($method)
    {
        $methodId = $method["methodId"];
        $visibility = $method["visibility"];
        $typeModifier = $method["typeModifier"];
        $instanceType = $method["instanceType"];
        $methodName = $method["methodName"];
        $returnType = $method["returnType"];
        $params = ClassDiagramService::selectParamByMethodId($methodId);
        $params = DataGenerator::sortBySequenceIndex($params);
        $paramList = self::generateParamList($params);
        self::$content .= "\t" . $visibility . " ";
        if ($instanceType == Constant::STATIC_INSTANCE) {
            self::$content .= strtolower($instanceType) . " ";
        }
        self::$content .= $returnType . $typeModifier . " " . $methodName . "(" . $paramList . "){\r\n";
        if (count($params) > 0) {
            self::generatePrintLine($params);
        }
        self::generateReturnStatement($returnType, $typeModifier);
        self::closeMethod();
    }

    private static function generateParamList($paramList)
    {
        $paramListStr = "";
        foreach ($paramList as $param) {
            $dataType = $param["dataType"];
            if ($dataType == "string") {
                $dataType = "String"; // Upper first character case to match Java string declaration
            }
            $typeModifier = $param["typeModifier"];
            $paramName = $param["paramName"];
            $paramListStr = $paramListStr . $dataType . "" . $typeModifier . " " . $paramName . ",";
        }
        $lastCharacter = substr($paramListStr, - 1);
        if ($lastCharacter == ",") {
            $paramListStr = substr($paramListStr, 0, - 1);
        }
        return $paramListStr;
    }

    private static function generatePrintLine($params)
    {
        foreach ($params as $param) {
            $paramName = $param["paramName"];
            self::$content .= "\t\tSystem.out.println(" . $paramName . ");\r\n";
        }
    }

    private static function generateReturnStatement($returnType, $typeModifier)
    {
        if ($typeModifier!="") {
            self::$content .= "\t\treturn null;\r\n";
        } else {
            echo $returnType;
            self::$content .= "\t\treturn ".\DataGenerator::getRandomData($returnType).";\r\n";
        }
    }

    private static function closeMethod()
    {
        self::$content .= "\t}\r\n";
    }

    private static function closeClass()
    {
        self::$content .= "}\r\n";
    }
}

