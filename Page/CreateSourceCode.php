<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $filename = $_POST['filename'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang);
    class SourceCodeGenerator{
        private static $file;
        private static $graphID;
        private static $diagramID;
        private static $classID;
        private static $filename;
        private static $sourceType;
        private static $sourceLang;
        private static $root;
        public static function createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang){
           self::$graphID = $graphID;
           self::$diagramID = $diagramID;
           self::$classID = $classID;
           self::$filename = $filename;
           self::$sourceType = $sourceType;
           self::$sourceLang = $sourceLang;
           self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
           SourceCodeService::initialSourceCodeDatabase();
           self::createFile();
        }
        private static function createFile(){
            //echo $graphID." ".$diagramID." ".$classID." ".$filename." ".$sourceType." ".$sourceLang;
            if(self::$sourceLang == 'Java'){
                $filePath = self::$root."/Source Code Files/".self::$filename.".java";
                self::$file = fopen($filePath,'w');
            }else{
                $filePath = self::$root."/Source Code Files/".self::$filename.".php";
                self::$file = fopen($filePath,'w');
            }
            SourceCodeService::insertFile(self::$filename, self::$sourceType, self::$sourceLang, $filePath);
            if(self::$sourceType == 'stub'){
                self::identifyStub();
            }else{
                self::identifyDriver();
            }
        }
        private static function identifyStub(){
            $messageList = CallGraphService::selectMessageBySentNodeID(self::$graphID,self::$classID);
            $methodList = self::getMethodListFromMessageList($messageList);
            self::writeStubFile($methodList);
        }
        private static function identifyDriver(){
            $messageList = CallGraphService::selectMessageByReceivedNodeID(self::$graphID,self::$classID);
            $methodList = self::getMethodListFromMessageList($messageList);     
            self::writeDriverFile($methodList);       
        }
        private static function getMethodListFromMessageList($messageList){
            $methodList = array();
            foreach($messageList as $message){
                $node = CallGraphService::selectNodeByNodeID(self::$graphID,$message['receivedNodeID']);
                $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID,$node['nodeName']);
                $method = ClassDiagramService::selectMethodFromMessageName(self::$diagramID,$class['className'],$message['messageName']);
                array_push($methodList,$method);
            }
            return $methodList;
        }
        private static function writeStubFile($methodList){
            if(self::$sourceLang === "PHP"){
                fwrite(self::$file,"<?php\n");
            }
            $txt = "class ".self::$filename."{\n";
            fwrite(self::$file, $txt);
            if(self::$sourceLang === "Java"){
                foreach($methodList as $method){
                    self::writeJavaMethod($method);
                } 
            }else{
                foreach($methodList as $method){
                    self::writePHPMethod($method);
                } 
            }
            self::closeFile();
        }
        private static function writeDriverFile($methodList){
            if(self::$sourceLang === 'Java'){
                $txt = "import static org.junit.jupiter.api.Assertions.*;\n";
                fwrite(self::$file, $txt);
                $txt = "import org.junit.jupiter.api.Test;\n";
                fwrite(self::$file, $txt);
                $txt = "class ".self::$filename."{\n";
            }else{
                fwrite(self::$file, "<?php\n");
                $txt = "use PHPUnit\Framework\TestCase;\n";
                fwrite(self::$file, $txt);
                $txt = "class ".self::$filename." extends TestCase{\n";
            }
            fwrite(self::$file, $txt);
            self::closeFile();
        }
        private static function writeJavaMethod($method){
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);
            $txt = "\tpublic ".$method['returnType']." ".$method['methodName']."(";
            fwrite(self::$file, $txt);
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
            fwrite(self::$file,"){\n");
            foreach($parameterList as $parameter){
                $txt = "\t\tSystem.out.println(".$parameter['parameterName'].");\n";
                fwrite(self::$file,$txt);
            }
            if($method['typeModifier'] === ""){
                $returnType = self::getDefaultValue($method['returnType']);
            }else{
                $returnType = "null";
            }
            $txt = "\t\treturn ".$returnType.";\n";
            fwrite(self::$file,$txt);
            fwrite(self::$file,"\t}\n");
        }
        private static function getDefaultValue($returnType){
            switch($returnType){
                case "float" : return "0.0";
                case "int" : return "0";
                case "double" : return "0.0";
                case "char" : return "''";
                case "string" : return "\"\"";
                case "boolean" : return "false";
                case "long" : return "0";
                case "short" : return "0";
                case "byte" : return "0";
                default : return "null";
            }
        }
        private static function writePHPMethod($method){
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);
            $txt = "\tpublic function ".$method['methodName']."(";
            fwrite(self::$file, $txt);
            foreach($cit as $parameter){
                $txt = "$".$parameter['parameterName'];
                fwrite(self::$file, $txt);
                if($cit->hasNext()){
                    fwrite(self::$file, ", ");
                }
            }
            fwrite(self::$file,"){\n");
            foreach($parameterList as $parameter){
                $txt = "\t\tprint_r($".$parameter['parameterName'].");\n";
                fwrite(self::$file,$txt);
            }
            if($method['typeModifier'] === ""){
                $returnType = self::getDefaultValue($method['returnType']);
            }else{
                $returnType = "null";
            }
            $txt = "\t\treturn ".$returnType.";\n";
            fwrite(self::$file,$txt);
            fwrite(self::$file,"\t}\n");
        }
        private static function closeFile(){
            $txt = "}";
            fwrite(self::$file, $txt);
            if(self::$sourceLang === "PHP"){
                fwrite(self::$file,"\n?>\n");
            }
            fclose(self::$file);
        }
    }
?>