<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/Database/ClassDiagramService.php";
    include_once "$root/Diagram/ClassDiagram/ObjectClass.php";
    include_once "$root/Diagram/ClassDiagram/Method.php";
    include_once "$root/Diagram/ClassDiagram/Parameter.php";
    use ClassDiagram\ObjectClass;
    use ClassDiagram\Method;
    use ClassDiagram\Parameter;
    class SimpleCDProcessor{
        private static $diagramID;
        public static function processSimpleCD($xml, $diagramID){
            self::$diagramID = $diagramID;
            $packageList = $xml->Models;
            self::identifyPackageSimple($packageList, "");
            self::identifyClassSimple($packageList, "");
        }
        private static function identifyPackageSimple($packageList, $packagePath){
            foreach($packageList->Package as $package){
                $packagePath = $packagePath."/".$package['Name'];
                self::identifyPackageSimple($package->ModelChildren,$packagePath);
                self::identifyClassSimple($package->ModelChildren,$packagePath);
                $packagePath = "";
            }
        }
        private static function identifyClassSimple($classList, $packagePath){
            foreach($classList->Class as $class){
                $className = $class['Name'];
                $classObject = new ObjectClass($className);
                $classObject->setPackagePath($packagePath);
                $classObject->setClassType(self::getClassType($class));
                ClassDiagramService::insertToClassTable(self::$diagramID, $classObject);
                self::identifyMethodSimple($class->ModelChildren, $className);
            }
        }
        private static function identifyMethodSimple($methodList, $className){
            foreach($methodList->Operation as $method){
                $methodID = $method['Id'];
                $methodObject = new Method($methodID, $method['Name']);
                $methodObject->setReturnType(self::getReturnType($method->ReturnType));
                $methodObject->setReturnTypeModifier($method['TypeModifier']);
                $methodObject->setVisibility($method['Visibility']);
                $methodObject->setIsStatic(self::getIsStaticBooleanValueSimple($method['Scope']));
                $methodObject->setIsAbstract(self::getIsAbstractBooleanValueSimple($method['Abstract']));
                ClassDiagramService::insertToMethodTable(self::$diagramID, $className, $methodObject);
                self::identifyParameterSimple($method->ModelChildren, $methodID);
            }
        }
        private static function identifyParameterSimple($parameterList, $methodID){
            foreach($parameterList->children() as $parameter){
                $parameterObject = new Parameter($parameter['Id'], $parameter['Name']);
                $parameterObject->setParamType(self::getParameterType($parameter->Type));
                $parameterObject->setTypeModifier($parameter['TypeModifier']);
                ClassDiagramService::insertToParameterTable(self::$diagramID, $methodID, $parameterObject);
            }
        }
        private static function getReturnType($returnType){
            if(isset($returnType->DataType)){
                return $returnType->DataType['Name'];
            }
            else if(isset($returnType->Class)){
                return $returnType->Class['Name'];
            }
        }
        private static function getParameterType($type){
            if(isset($type->DataType)){
                return $type->DataType['Name'];
            }
            else if(isset($type->Class)){
                return $type->Class['Name'];
            }
        }
        private static function getIsStaticBooleanValueSimple($isStatic){
            if($isStatic=="instance"){
                return 0;
            }
            return 1;
        }
        private static function getIsAbstractBooleanValueSimple($isAbstract){
            if($isAbstract=="true"){
                return 1;
            }
            return 0;
        }
        private static function getClassType($class){
            //TODO Identify concrete descendance when class is an abstract or an interface
            if($class['Abstract']=="true"){
                return ObjectClass::ABSTRACT_CLASS;
            }else if(isset($class->Stereotypes)){
                if($class->Stereotypes->Stereotype['Name'] == "Interface"){
                    return ObjectClass::INTERFACE_CLASS;
                }
            }
            return ObjectClass::CONCRETE_CLASS;
        }
    }
?>