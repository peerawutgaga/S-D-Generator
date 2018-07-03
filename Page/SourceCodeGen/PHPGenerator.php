<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    class PHPGenerator{
        private static $root;
        private static $file;
        private static function getDefaultValue($returnType){
            switch($returnType){
                case "float" : return "0.0";
                case "int" : return "0";
                case "double" : return "0.0";
                case "boolean" : return "false";
                case "long" : return "0";
                case "short" : return "0";
                case "byte" : return "0";
                default : return "null";
            }
        }
        public static function createStub($stub){
            self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $filename = $stub['className']."Stub.php";
            $success = self::createFile($filename,"stub");
            if(!$success){
                return $filename;
            }
            self::initialStubHeader($stub['className']);
            $methodList = ClassDiagramService::selectAllMethodFromClassName($stub['diagramID'],$stub['className']);
            foreach($methodList as $method){
                    self::writeMethod($method);
                }
            self::closeFile();
            return $filename;
        }
        public static function createDriver($driver){
            self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $filename = $stub['className']."Driver.php";
            $success = self::createFile($filename,"driver");
            if(!$success){
                return $filename;
            }
            self::initialDriverHeader($driver);
            $methodList = ClassDiagramService::selectAllMethodFromClassName($driver['diagramID'],$driver['className']);
            foreach($methodList as $method){
                self::writeUnitTest($method);
            }
            self::closeFile();
            return $filename;
        }
        private static function createFile($filename,$sourceCodeType){
            $filepath = "../Source Code Files/".$filename.".txt";
            $success = SourceCodeService::insertFile($filename, $sourceCodeType, "PHP", $filepath);
            if(!$success){
                return false;
            }
            self::$file = fopen($filepath,"w");
            return true;
        }
        private static function initialStubHeader($className){
            fwrite(self::$file,"<?php\n");
            $txt = "\tclass ".$className."Stub{\n";
            fwrite(self::$file,$txt);
        }
        private static function initialDriverHeader($driver){
            fwrite(self::$file,"<?php\n");
            $txt = "\tuse PHPUnit\Framework\TestCase;\n";
            fwrite(self::$file, $txt);
            $txt = "\t\$root = realpath(\$_SERVER[\"DOCUMENT_ROOT\"]);\n";
            fwrite(self::$file, $txt);
            if(isset($driver['packagePath'])){
                $path = $driver['packagePath'];
                $txt = "\tinclude \"\$root".$path."/".$driver['className'].".php\";\n";
                fwrite(self::$file, $txt);
            }
            $txt = "\tclass ".$driver['className']."Driver extends TestCase{\n";
            fwrite(self::$file,$txt);  
        }
        private static function writeMethod($method){
            if($method['visibility']!='public'){
                return;
            }
            if($method['isStatic']){
                $txt = "\t\t".$method['visibility']." static function ".$method['methodName']."(";
            }else{
                $txt = "\t\t".$method['visibility']." function ".$method['methodName']."(";
            }
            fwrite(self::$file, $txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID($method['diagramID'],$method['methodID']);
            self::writeParameter($parameterList);
            fwrite(self::$file,"){\n");
            self::writePrint($parameterList);
            if(isset($method['returnType'])){
                if($method['returnType'] != "void"){
                    $defaultValue = self::getDefaultValue($method['returnType']);
                    $txt = "\t\t\treturn ".$defaultValue.";\n";
                    fwrite(self::$file, $txt);
                }
            }
            fwrite(self::$file,"\t\t}\n");
        }
        private static function writeUnitTest($method){
            if($method['visibility']!='public'){
                return;
            }
            if(!isset($method['returnType'])){
                return;
            }
            $methodName = ucfirst($method['methodName']);
            $txt = "\t\tfunction test".$methodName."(){\n";
            fwrite(self::$file, $txt);
            if($method['isStatic'] == 1){
                self::callStaticMethod($method);
            }else{
                self::declareClassInstance($method);
                self::callMethodFromInstance($method);
            }
            self::writeAssert($method);
            fwrite(self::$file,"\t\t}\n");
        }
        private static function writePrint($parameterList){
            foreach($parameterList as $parameter){
                $txt = "\t\t\tprint(\$".$parameter['parameterName'].");\n";
                fwrite(self::$file,$txt);
            }
        }
        private static function writeParameter($parameterList){
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);     
            foreach($cit as $parameter){
                $txt = "\$".$parameter['parameterName'];
                fwrite(self::$file, $txt);
                if($cit->hasNext()){
                    fwrite(self::$file, ", ");
                }
            }
        }
        private static function writeInput($parameterList){
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);     
            foreach($cit as $parameter){
                $value = self::getDefaultValue($parameter['parameterType']);
                fwrite(self::$file, $value);
                if($cit->hasNext()){
                    fwrite(self::$file, ", ");
                }
            }
        }
        private static function callStaticMethod($method){
            if($method['returnType'] == 'void'){
                $txt = "\t\t\t".$method['className']."::".$method['methodName']."(";
            }else{
                $returnType = $method['returnType'];
                $txt = "\t\t\t\$returnValue = ".$method['className']."::".$method['methodName']."(";
            }
            fwrite(self::$file,$txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID($method['diagramID'],$method['methodID']);
            self::writeInput($parameterList);
            fwrite(self::$file,");\n");
        }
        private static function callMethodFromInstance($method){
            $instance = lcfirst($method['className']);
            if($method['returnType'] == 'void'){
                $txt = "\t\t\t".$instance.".".$method['methodName']."(";
            }else{
                $returnType = $method['returnType'];
                $txt = "\t\t\t\$returnValue = ".$instance.".".$method['methodName']."(";
            }
            fwrite(self::$file,$txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID($method['diagramID'],$method['methodID']);
            self::writeInput($parameterList);
            fwrite(self::$file,");\n");
        }
        private static function declareClassInstance($method){
            $instance = lcfirst($method['className']);
            $constructor = ClassDiagramService::selectMethodByMethodName($method['diagramID'],$method['className'],$method['className']);
            if($constructor == null){
                $constructor = ClassDiagramService::selectMethodFromMethodName(self::$diagramID,$method['className'],"__construct");
                if($constructor ==null){
                    $txt = "\t\t\t\$".$instance." = new ".$method['className'].";\n";
                    fwrite(self::$file, $txt);
                }
            }
            $parameterList = ClassDiagramService::selectParameterByMethodID($method['diagramID'],$constructor['methodID']);
            $txt = "\t\t\t\$".$instance." = new ".$method['className']."(";
            fwrite(self::$file, $txt);
            self::writeInput($parameterList);
            fwrite(self::$file, ");\n");
        }
        private static function writeAssert($method){
            if($method['returnType']== "void"){
                return;
            }
            $txt = "\t\t\t\$expectedValue;\n";
            fwrite(self::$file, $txt);
            $txt = "\t\t\t\$this->assertEquals(\$expectedValue, \$returnValue);\n";
            fwrite(self::$file, $txt);
        }
        private static function closeFile(){
            fwrite(self::$file,"\t}\n");
            fwrite(self::$file,"?>\n");
            fclose(self::$file);
        }
    }
?>