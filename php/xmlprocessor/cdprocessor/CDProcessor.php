<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/php/database/ClassDiagramService.php';
require_once $root . '/php/utilities/Logger.php';
require_once $root . '/php/utilities/Constant.php';

class CDProcessor
{
   
    private static $diagramId;
    
    private static $xml;
    private static $classList;
    private static $inheritanceList;

    public static function readClassDiagramFile($filename, $filePath)
    {
        self::$xml = simplexml_load_file($filePath);
        if (self::$xml === false) {
            $errorMessage = "Failed loading XML: " . "<br>";
            foreach (libxml_get_errors() as $error) {
                $errorMessage = $errorMessage . "\n" . $error->message;
            }
            Logger::logDatabaseError("CDProcessor", $errorMessage);
            die("XMLProcessor Terminated. Please open console to see errors");
        }
        if (self::$xml['Xml_structure'] == 'simple') {
            self::$diagramId = ClassDiagramService::insertIntoDiagram($filename, $filePath);
            self::processClassDiagram();
        } else {
            Script::alert("Traditional XML format does not support by the tool.");
        }
    }

    private static function processClassDiagram()
    {
        $packages = self::$xml->Models->Package;
        self::$classList = array();
        self::$inheritanceList = array();
        foreach ($packages as $package) {
            self::identifyPackage($package, "");
        }
        self::identifyInheritance();
    }

    private static function identifyPackage($package, $namespace)
    {
        $packageName = $package["Name"];
        if ($namespace == "") {
            $namespace = $packageName;
        } else {
            $namespace = $namespace . "." . $packageName;
        }
        $packageId = ClassDiagramService::insertIntoPackage(self::$diagramId, $packageName, $namespace);
        foreach ($package->ModelChildren->children() as $children) {
            if ($children->getName() == "Package") {
                self::identifyPackage($children, $namespace);
            } else if ($children->getName() == "Class") {
                self::identifyClass($packageId, $children);
            }
        }
    }

    private static function identifyClass($packageId, $class)
    {
        $className = $class["Name"];
        $classIdStr = $class["Id"];
        $classId = - 1;
        if ($class["Abstract"] == "true") {
            $classId = ClassDiagramService::insertIntoClass($packageId, $className, Constant::ABSTRACT_INSTANCE);
        } else if ($class->Stereotypes->Stereotype[0]["Name"] == "Interface") {
            $classId = ClassDiagramService::insertIntoClass($packageId, $className, Constant::INTERFACT_INSTANCE);
        } else {
            $classId = ClassDiagramService::insertIntoClass($packageId, $className, Constant::CONCRETE_INSTANCE);
        }
        if ($classId != - 1) {
            self::$classList[(string)$classIdStr] = $classId;
            $methodList = $class->ModelChildren->Operation;
            $innerClassList = $class->ModelChildren->Class;
            foreach ($innerClassList as $innerClass) {
                self::identifyClass($packageId, $innerClass);
            }
            self::identifyMethod($classId, $methodList);
        }
        if(isset($class->FromSimpleRelationships->Realization)){
            $realizations = $class->FromSimpleRelationships->Realization;
            self::identifyChildClassFromRealization($classIdStr,$realizations);
        }
       if(isset($class->FromSimpleRelationships->Generalization)){
            $generalizations = $class->FromSimpleRelationships->Generalization;
            self::identifyChildClassFromGeneralization($classIdStr,$generalizations);
        }
    }

    private static function identifyMethod($classId, $methodList)
    {
        foreach ($methodList as $method) {
            $methodName = $method["Name"];
            $visibility = $method["Visibility"];
            $typeModifier = $method["TypeModifier"];
            $isConstructor = 0;
            $instanceType = Constant::CONCRETE_INSTANCE;
            if (isset($method->ReturnType)) {
                if (isset($method->ReturnType->DataType)) {
                    $returnType = $method->ReturnType->DataType["Name"];
                } else if (isset($method->ReturnType->Class)) {
                    $returnType = $method->ReturnType->Class["Name"];
                }
            } else {
                $returnType = "void";
                $isConstructor = "1";
            }
            if ($method["Abstract"] == "true") {
                $instanceType = Constant::ABSTRACT_INSTANCE;
            } else if ($method["Scope"] == "classifier") {
                $instanceType = Constant::STATIC_INSTANCE;
            }
            $methodId = ClassDiagramService::insertIntoMethod($classId, $methodName, $visibility, $returnType, $typeModifier, $instanceType, $isConstructor);
            if ($method != - 1) {
                $paramList = $method->ModelChildren->Parameter;
                if (isset($paramList)) {
                    self::identifyParam($methodId, $paramList);
                }
            }
        }
    }

    private static function identifyParam($methodId, $paramList)
    {
        $seqIdx = 1;
        foreach ($paramList as $param) {
            $paramName = $param["Name"];
            $typeModifier = $param["TypeModifier"];
            if (isset($param->Type->DataType)) {
                $dataType = $param->Type->DataType["Name"];
            } else if (isset($param->Type->Class)) {
                $dataType = $param->Type->Class["Name"];
            }else{
                $dataType = $param["Type"];
            }
            ClassDiagramService::insertIntoParam($methodId, $paramName, $dataType, $typeModifier, $seqIdx);
            $seqIdx++;
        }
    }
    private static function identifyChildClassFromRealization($parentId,$realizations){       
        foreach($realizations as $realization){
            $realizationId = $realization["Idref"];            
            $childClasses = self::$xml->xpath("//Class[ToSimpleRelationships/Realization[@Idref='$realizationId']]");
            if(isset($childClasses)){
                foreach ($childClasses as $childClass){
                    $childId = $childClass["Id"];
                    array_push(self::$inheritanceList,array("parent"=>(string)$parentId,"child"=>(string)$childId));
                }
            }
        }
    }
    private static function identifyChildClassFromGeneralization($parentId,$generalizations){
        foreach($generalizations as $generalization){
            $generalizationId = $generalization["Idref"];
            $childClasses = self::$xml->xpath("//Class[ToSimpleRelationships/Generalization[@Idref='$generalizationId']]");
            if(isset($childClasses)){
                foreach ($childClasses as $childClass){
                    $childId = $childClass["Id"];
                    array_push(self::$inheritanceList,array("parent"=>(string)$parentId,"child"=>(string)$childId));
                }
            }
        }
    }
    private static function identifyInheritance(){
        foreach (self::$inheritanceList as $inheritance){
            $parentId = self::$classList[$inheritance["parent"]];
            $childId = self::$classList[$inheritance["child"]];
            ClassDiagramService::insertIntoInheritance($parentId, $childId);
        }
    }
}
?>