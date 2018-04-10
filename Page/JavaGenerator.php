<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    require_once "$root/PHP/Script.php";
    class JavaGenerator{
        private static $root;
        private static $file;
        private static function getDefaultValue($returnType){
            switch($returnType){
                case "float" : return "0.0";
                case "int" : return "0";
                case "double" : return "0.0";
                case "char" : return "'\\u0000'";
                case "boolean" : return "false";
                case "long" : return "0";
                case "short" : return "0";
                case "byte" : return "0";
                default : return "null";
            }
        }
        public static function createStub($stub){
            self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = self::createFile($stub['className'],"stub");
            if(!$success){
                return;
            }
            self::initialStubHeader($stub['className']);
            $methodList = ClassDiagramService::selectAllMethodFromClassName($stub['diagramID'],$stub['className']);
            foreach($methodList as $method){
                self::writeMethod($method);
            }
            self::closeFile();
        }
        public static function createDriver($driver){
            self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = self::createFile($driver['className'],"driver");
            if(!$success){
                return;
            }
            self::initialDriverHeader($driver);
            self::closeFile();

        }
        private static function createFile($className,$sourceCodeType){
            if($sourceCodeType == "stub"){
                $filename = $className."Stub.java";
            }else{
                $filename = $className."Driver.java";
            }
            $filepath = self::$root."/Source Code Files/".$filename.".txt";
            $success = SourceCodeService::insertFile($filename, $sourceCodeType, "Java", $filepath);
            if(!$success){
                return false;
            }
            self::$file = fopen($filepath,"w");
            return true;
        }
        private static function initialStubHeader($className){
            $txt = "class ".$className."Stub {\n";
            fwrite(self::$file,$txt);
        }
        private static function initialDriverHeader($driver){
            $txt = "import static org.junit.jupiter.api.Assertions.*;\n";
            fwrite(self::$file, $txt);
            $txt = "import org.junit.jupiter.api.Test;\n";
            fwrite(self::$file, $txt);
            if(isset($driver['packagePath'])){
                echo $driver['packagePath']."<br>";
            }
        }
        private static function writeMethod($method){
            if($method['visibility']!='public'){
                return;
            }
            $txt = "\t".$method['visibility']." ".$method['returnType']." ".$method['methodName']."(";
            fwrite(self::$file, $txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID($method['diagramID'],$method['methodID']);
            self::writeParameter($parameterList);
            fwrite(self::$file,"){\n");
            self::writeSysOut($parameterList);
            if(isset($method['returnType'])){
                if($method['returnType'] != "void"){
                    $defaultValue = self::getDefaultValue($method['returnType']);
                    $txt = "\t\treturn ".$defaultValue.";\n";
                    fwrite(self::$file, $txt);
                }
            }
            fwrite(self::$file,"\t}\n");
        }
        
        private static function writeParameter($parameterList){
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);     
            foreach($cit as $parameter){
                $paramType = $parameter['parameterType'];
                if($paramType === 'string'){
                    $paramType = "String";
                }
                $txt = $paramType.$parameter['typeModifier']." ".$parameter['parameterName'];
                fwrite(self::$file, $txt);
                if($cit->hasNext()){
                    fwrite(self::$file, ", ");
                }
            }
        }
        private static function writeSysOut($parameterList){
            foreach($parameterList as $parameter){
                $txt = "\t\tSystem.out.println(".$parameter['parameterName'].");\n";
                fwrite(self::$file,$txt);
            }
        }
        private static function closeFile(){
            fwrite(self::$file,"}\n");
            fclose(self::$file);
        }
    }
?>