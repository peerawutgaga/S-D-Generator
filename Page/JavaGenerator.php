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
        private static function createFile($className,$sourceCodeType){
            $filename = $className."Stub.java";
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
        private static function writeMethod($method){
        
        }
        
        private static function writeParameter(){

        }
        private static function closeFile(){
            fwrite(self::$file,"}\n");
            fclose(self::$file);
        }
    }
?>