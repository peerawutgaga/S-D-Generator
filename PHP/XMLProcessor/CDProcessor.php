<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/ClassDiagramService.php";
    class CDProcessor{
        private static $conn;
        private static $diagramID;
        private static $dataTypeRef;
        public static function readClassDiagram($filename,$targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                Script::consoleLog("Failed loading XML: ");
                foreach(libxml_get_errors() as $error) {
                    Script::consoleLog("<br>", $error->message);
                }
                die("XMLProcessor Terminated. Please open console to see errors");
            }
            self::saveFileToDB($filename,$targetFile);
            if($xml['Xml_structure'] == 'simple'){
                self::processSimpleCD($xml);
            }else{
               self::processTraditionalCD($xml);
            }
        }
        private static function saveFileToDB($filename,$fileTarget){
            self::$conn = Database::connectToDB();
            // Database::dropDatabase(self::$conn,'classDiagram');
            // ClassDiagramService::initialClassDiagramDatabase(self::$conn, $filename, $fileTarget);
            Database::selectDB(self::$conn,'classDiagram');
            ClassDiagramService::insertToDiagramTable(self::$conn, $filename, $fileTarget);
            self::$diagramID = ClassDiagramService::selectFromDiagramTable('diagramID','diagramName',$filename);
        }
        private static function processSimpleCD($xml){
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
        private static function processTraditionalCD($xml){
           $modelList = $xml->Models;
           self::$dataTypeRef = array();
           self::collectDataTypeRef($modelList);
           self::identifyPackageTraditional($modelList, "");
           self::$conn->close();
        }
        private static function collectDataTypeRef($modelList){
            foreach($modelList->Model as $model){
                if($model['modelType'] == "DataType"){
                    self::$dataTypeRef[(string)$model['id']] = (string)$model['name'];
                }else if($model['modelType']=="Class"){
                    self::$dataTypeRef[(string)$model['id']] = (string)$model['name'];
                }else if($model['modelType']=="Package"){
                    self::collectDataTypeRef($model->ChildModels);
                }
            }
        }
        private static function identifyPackageTraditional ($modelList, $packagePath){
            foreach($modelList->Model as $model){
                if($model['modelType']=="Class"){
                    self::identifyClassTraditional($model,$packagePath);
                }else if($model['modelType']=="Package"){
                    $packagePath = $packagePath."/".$model['name'];
                    self::identifyPackageTraditional($model->ChildModels,$packagePath);
                    $packagePath = "";
                }
            }
        }
        private static function identifyClassTraditional($class, $packagePath){
            $className = $class['name'];
            ClassDiagramService::insertToClassTable(self::$conn, self::$diagramID, $className, $packagePath);
            self::identifyMethodTraditional($class->ChildModels, $className);

        }
        private static function identifyMethodTraditional($methodList, $className){
            foreach($methodList->Model as $method){
                if($method['modelType']=="Operation"){
                    $methodID = $method['id'];
                    $methodName = $method['name'];
                    $returnType = self::identifyType($method->ModelProperties->TextModelProperty);
                    $visibility = self::getVisibility($method->ModelProperties);
                    $typeModifier = self::getTypeModifier($method->ModelProperties);
                    $isStatic = self::getIsStaticValueTraditional($method->ModelProperties);
                    ClassDiagramService::insertToMethodTable(self::$conn, self::$diagramID,$className, 
                    $methodID, $methodName, $returnType,$visibility,$typeModifier, $isStatic);
                    self::identifyParameterTraditional($method->ChildModels, $methodID);
                }
            }
        }
        private static function identifyParameterTraditional($parameterList, $methodID){
           foreach($parameterList->Model as $parameter){
               $parameterID = $parameter['id'];
               $parameterName = $parameter['name'];
               $parameterType = self::identifyType($parameter->ModelProperties->TextModelProperty);
               $typeModifier = self::getTypeModifier($parameter->ModelProperties);
               ClassDiagramService::insertToParameterTable(self::$conn, self::$diagramID, 
               $methodID,$parameterID, $parameterName, $parameterType, $typeModifier);
            }
        }     
        private static function getTypeModifier($modelProperties){
            foreach($modelProperties->StringProperty as $strProp){
                if($strProp['name']=="typeModifier"){
                    return $strProp['value'];
                }
            }
        }
        private static function identifyType($textModelProperties){
            if(isset($textModelProperties->ModelRef)){
                $typeID = (string)$textModelProperties->ModelRef['id'];
                return self::$dataTypeRef[$typeID];
            }
        }
        private static function getIsStaticValueTraditional($modelProperties){
            foreach($modelProperties->StringProperty as $strProp){
                if($strProp['name']=="scope"){
                    if($strProp['value'] == "instance"){
                        return 0;
                    }
                    return 1;
                }
            }
        }
        private static function getVisibility($modelProperties){
            foreach($modelProperties->StringProperty as $strProp){
                if($strProp['name']=="visibility"){
                    return $strProp['value'];
                }
            }
        }
    }
?>