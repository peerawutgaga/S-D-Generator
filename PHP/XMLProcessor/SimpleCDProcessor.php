<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/ClassDiagramService.php";
    class SimpleCDProcessor{
        private static $conn;
        private static $diagramID;
        public static function processSimpleCD($xml, $conn, $diagramID){
            self::$conn = $conn;
            self::$diagramID = $diagramID;
            $packageList = $xml->Models;
            self::identifyPackageSimple($packageList, "");
            self::identifyClassSimple($packageList, "");
            self::$conn->close();
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
                ClassDiagramService::insertToClassTable(self::$conn, self::$diagramID, $className,$packagePath);
                self::identifyMethodSimple($class->ModelChildren, $className);
            }
        }
        private static function identifyMethodSimple($methodList, $className){
            foreach($methodList->Operation as $method){
                $methodID = $method['Id'];
                $methodName = $method['Name'];
                $returnType = self::getReturnType($method->ReturnType);
                $visibility = $method['Visibility'];
                $typeModifier = $method['TypeModifier'];
                $isStatic = self::getIsStaticValueSimple($method['Scope']);
                ClassDiagramService::insertToMethodTable(self::$conn, self::$diagramID, $className, $methodID, 
                $methodName, $returnType, $visibility,$typeModifier, $isStatic);
                self::identifyParameterSimple($method->ModelChildren, $methodID);
            }
        }
        private static function identifyParameterSimple($parameterList, $methodID){
            foreach($parameterList->children() as $parameter){
                $parameterID = $parameter['Id'];
                $parameterName = $parameter['Name'];
                $parameterType = self::getParameterType($parameter->Type);
                $typeModifier = $parameter['TypeModifier'];
                ClassDiagramService::insertToParameterTable(self::$conn, self::$diagramID, $methodID, $parameterID, 
                $parameterName, $parameterType, $typeModifier);
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
        private static function getIsStaticValueSimple($isStatic){
            if($isStatic == "instance"){
                return 0;
            }
            return 1;
        }
    }
?>