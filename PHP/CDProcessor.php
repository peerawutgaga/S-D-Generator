<?php
    require_once "ClassDiagramService.php";
    class CDProcessor{
        private static $conn;
        private static $diagramID;
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
            Database::dropDatabase(self::$conn,'classDiagram');
            ClassDiagramService::initialClassDiagramDatabase(self::$conn, $filename, $fileTarget);
            ClassDiagramService::insertToDiagramTable(self::$conn, $filename, $fileTarget);
            self::$diagramID = ClassDiagramService::selectFromDiagramTable('diagramID','diagramName',$filename);
        }
        private static function processSimpleCD($xml){
            $classList = $xml->Models->Package->ModelChildren->Package->ModelChildren;
            self::identifyClassSimple($classList);
            self::$conn->close();
        }
        private static function identifyClassSimple($classList){
            $classID; $className;
            foreach($classList->children() as $class){
                $classID = $class['Id'];
                $className = $class['Name'];
                ClassDiagramService::insertToClassTable(self::$conn, self::$diagramID, $classID, $className);
                self::identifyMethodSimple($class->ModelChildren, $classID);
            }
        }
        private static function identifyMethodSimple($methodList, $classID){
            $methodID; $methodName; $returnType; $typeModifier;
            foreach($methodList->Operation as $method){
                $methodID = $method['Id'];
                $methodName = $method['Name'];
                $returnType = self::getReturnType($method->ReturnType);
                $typeModifier = $method['TypeModifier'];
                ClassDiagramService::insertToMethodTable(self::$conn, self::$diagramID, $classID, $methodID, $methodName, $returnType, $typeModifier);
                self::identifyParameterSimple($method->ModelChildren, $methodID);
            }
        }
        private static function identifyParameterSimple($parameterList, $methodID){
            $parameterID; $parameterName; $parameterType; $typeModifier;
            foreach($parameterList->children() as $parameter){
                $parameterID = $parameter['Id'];
                $parameterName = $parameter['Name'];
                $parameterType = self::getParameterType($parameter->Type);
                $typeModifier = $parameter['TypeModifier'];
                ClassDiagramService::insertToParameterTable(self::$conn, self::$diagramID, $methodID, $parameterID, $parameterName, $parameterType, $typeModifier);
            }
        }
        private static function getReturnType($returnType){
            if(isset($returnType->DataType)){
                return $returnType->DataType['Name'];
            }
            else if(isset($returnType->Class)){
                return $returnType->Class['Name'];
            }
            return "void";
        }
        private static function getParameterType($type){
            if(isset($type->DataType)){
                return $type->DataType['Name'];
            }
            else if(isset($type->Class)){
                return $type->Class['Name'];
            }
        }
        private static function processTraditionalCD($xml){
            
        }
        private static function identifyClassTraditional($classList){
            
        }
        private static function identifyMethodTraditional($methodList){

        }
        private static function identifyParameterTraditional($parameterList){
            
        }
    }
?>