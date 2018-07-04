<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/ClassDiagramService.php";
    class TraditionalCDProcessor{
        private static $conn;
        private static $diagramID;
        private static $dataTypeRef;
        public static function processTraditionalCD($xml,$conn,$diagramID){
            $modelList = $xml->Models;
            self::$conn = $conn;
            self::$diagramID = $diagramID;
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