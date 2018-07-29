<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/Database/ClassDiagramService.php";
    include_once "$root/Diagram/ClassDiagram/ObjectClass.php";
    include_once "$root/Diagram/ClassDiagram/Method.php";
    include_once "$root/Diagram/ClassDiagram/Parameter.php";
    use ClassDiagram\ObjectClass;
    use ClassDiagram\Method;
    use ClassDiagram\Parameter;
    class TraditionalCDProcessor{
        private static $diagramID;
        private static $dataTypeRef;
        public static function processTraditionalCD($xml,$diagramID){
            $modelList = $xml->Models;
            self::$diagramID = $diagramID;
            self::$dataTypeRef = array();
            self::collectDataTypeRef($modelList);
            self::identifyPackageTraditional($modelList, "");
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
             $classObject = new ObjectClass($className);
             $classObject->setPackagePath($packagePath);
             $classObject->setClassType(ObjectClass::CONCRETE_CLASS);
             ClassDiagramService::insertToClassTable(self::$diagramID, $classObject);
             self::identifyMethodTraditional($class->ChildModels, $className);
 
         }
         private static function identifyMethodTraditional($methodList, $className){
             foreach($methodList->Model as $method){
                 if($method['modelType']=="Operation"){
                     $methodID = $method['id'];
                     $methodObject = new Method($methodID, $method['name']);
                     $methodObject->setReturnType(self::identifyType($method->ModelProperties->TextModelProperty));
                     $methodObject->setReturnTypeModifier(self::getTypeModifier($method->ModelProperties));
                     $methodObject->setVisibility(self::getVisibility($method->ModelProperties));
                     $methodObject->setIsStatic(self::getIsStaticValueTraditional($method->ModelProperties));
                     //TODO Identify isAbstract
                     $methodObject->setIsAbstract(0);
                     ClassDiagramService::insertToMethodTable(self::$diagramID,$className, $methodObject);
                     self::identifyParameterTraditional($method->ChildModels, $methodID);
                 }
             }
         }
         private static function identifyParameterTraditional($parameterList, $methodID){
            foreach($parameterList->Model as $parameter){
                $parameterObject = new Parameter($parameter['id'],$parameter['name']);
                $parameterObject->setParamType(self::identifyType($parameter->ModelProperties->TextModelProperty));
                $parameterObject->setTypeModifier(self::getTypeModifier($parameter->ModelProperties));
                ClassDiagramService::insertToParameterTable(self::$diagramID, $methodID,$parameterObject);
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