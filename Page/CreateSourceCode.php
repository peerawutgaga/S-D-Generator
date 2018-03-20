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
        private static $importClassList;
        private static $fileID;
        public static function createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang){
           self::$graphID = $graphID;
           self::$diagramID = $diagramID;
           self::$classID = $classID;
           self::$filename = $filename;
           self::$sourceType = $sourceType;
           self::$sourceLang = $sourceLang;
           self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
           SourceCodeService::initialSourceCodeDatabase();
           if(!self::checkIfMessagesAreEmpty()){
            self::createFile();
           }
        }
        private static function checkIfMessagesAreEmpty(){
            if(self::$sourceType === "stub"){
                $messageList = CallGraphService::selectMessageBySentNodeID(self::$graphID,self::$classID);
            } else{
                $messageList = CallGraphService::selectMessageByReceivedNodeID(self::$graphID,self::$classID);
            }
            if(empty($messageList)){
                if(self::$sourceType === "stub"){
                    echo "stub error";
                }else{
                    echo "driver error";
                }
                return true;
            }
            return false;
        }
        private static function createFile(){
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
            self::$importClassList  = array();
            foreach($messageList as $message){
                $node = CallGraphService::selectNodeByNodeID(self::$graphID,$message['receivedNodeID']);
                $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID,$node['nodeName']);
                $method = ClassDiagramService::selectMethodFromMessageName(self::$diagramID,$class['className'],$message['messageName']);
                array_push($methodList,$method);
                array_push(self::$importClassList, $class);
            }
            self::$importClassList = array_unique(self::$importClassList,SORT_REGULAR);
            return $methodList;
        }
        private static function writeStubFile($methodList){
            if(self::$sourceLang === "PHP"){
                fwrite(self::$file,"<?php\n\t");
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
                self::writeJavaImport();
                $txt = "class ".self::$filename."{\n";
                fwrite(self::$file, $txt);
                foreach($methodList as $method){
                    self::writeJavaUnit($method);
                }
            }else{
                fwrite(self::$file, "<?php\n");
                $txt = "\tuse PHPUnit\Framework\TestCase;\n";
                fwrite(self::$file, $txt);
                self::writePHPInclude();
                $txt = "\tclass ".self::$filename." extends TestCase{\n";
                fwrite(self::$file, $txt);
                foreach($methodList as $method){
                    self::writePHPUnit($method);
                }
            }
            self::closeFile();
        }
        private static function writeJavaImport(){
            foreach(self::$importClassList as $importClass){
                $path = substr($importClass['packagePath'],1);
                $path = str_replace("/",".",$path);
                $txt = "import ".$path.".".$importClass['className'].";\n";
                fwrite(self::$file, $txt);
            }
        }
        private static function writePHPInclude(){
            $txt = "\t\$root = realpath(\$_SERVER[\"DOCUMENT_ROOT\"]);\n";
            fwrite(self::$file, $txt);
            foreach(self::$importClassList as $importClass){
                $path = $importClass['packagePath'];
                $txt = "\tinclude_once \"\$root".$path."/".$importClass['className'].".php\";\n";
                fwrite(self::$file, $txt);
            }
        }
        private static function writeJavaMethod($method){
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            $txt = "\tpublic ".$method['returnType']." ".$method['methodName']."(";
            fwrite(self::$file, $txt);
            self::writeJavaParameter($method,$parameterList);
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
        private static function writeJavaUnit($method){
            fwrite(self::$file, "\t@test\n");
            $txt = "\tvoid test".$method['methodName']."(){\n";
            fwrite(self::$file, $txt);
            if($method['isStatic']){
                $txt = "\t\t".$method['className'].".".$method['methodName']."(";
            }else{
                $classInstance = self::declareJavaConstructor($method);
                if($method['returnType'] == "void"){
                    $txt = "\t\t".$classInstance.".".$method['methodName']."(";
                }else{
                    $txt = "\t\tObject returnValue = ".$classInstance.".".$method['methodName']."(";
                }
            }
            fwrite(self::$file, $txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            self::setDefaultValueToParameter($parameterList);
            if($method['returnType'] != "void"){
                fwrite(self::$file, "\t\tassertEquals(expected, returnValue);\n");
            }
            fwrite(self::$file, "\t}\n");
        }
        private static function declareJavaConstructor($method){
            $classInstance = strtolower($method['className']);
            $constructor = ClassDiagramService::selectMethodFromMessageName(self::$diagramID,$method['className'],$method['className']);
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$constructor['methodID']);
            $txt = "\t\t".$method['className']." ".$classInstance." = new ".$method['className']."(";
            fwrite(self::$file, $txt);
            self::setDefaultValueToParameter($parameterList);
            return $classInstance;
        }
        private static function setDefaultValueToParameter($parameterList){
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);     
            foreach($cit as $parameter){
                $txt = self::getDefaultValue($parameter['parameterType']);
                fwrite(self::$file, $txt);
                if($cit->hasNext()){
                    fwrite(self::$file, ", ");
                }
            }
            fwrite(self::$file, ");\n");
        }
        private static function writeJavaParameter($method,$parameterList){
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
            fwrite(self::$file,"){\n");
        }
        private static function writePHPMethod($method){
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            $ait = new ArrayIterator($parameterList);
            $cit = new CachingIterator($ait);
            $txt = "\t\tpublic function ".$method['methodName']."(";
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
                $txt = "\t\t\tprint_r($".$parameter['parameterName'].");\n";
                fwrite(self::$file,$txt);
            }
            if($method['typeModifier'] === ""){
                $returnType = self::getDefaultValue($method['returnType']);
            }else{
                $returnType = "null";
            }
            $txt = "\t\t\treturn ".$returnType.";\n";
            fwrite(self::$file,$txt);
            fwrite(self::$file,"\t\t}\n");
        }
        private static function writePHPUnit($method){
            $txt = "\t\tfunction test".$method['methodName']."(){\n";
            fwrite(self::$file, $txt);
            if($method['isStatic']){
                $txt = "\t\t\t".$method['className']."::".$method['methodName']."(";
            }else{
                $classInstance = self::declarePHPConstructor($method);
                if($method['returnType'] != "void"){
                    $txt = "\t\t\t\$returnValue = \$".$classInstance."->".$method['methodName']."(";
                }else{
                    $txt = "\t\t\t\$".$classInstance."->".$method['methodName']."(";
                }
            }
            fwrite(self::$file, $txt);
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$method['methodID']);
            self::setDefaultValueToParameter($parameterList);
            if($method['returnType'] != "void"){
                fwrite(self::$file, "\t\t\t\$this->assertEquals(expected, \$returnValue);\n");
            }
            fwrite(self::$file, "\t\t}\n");
        }
        private static function declarePHPConstructor($method){
            $classInstance = strtolower($method['className']);
            $constructor = ClassDiagramService::selectMethodFromMessageName(self::$diagramID,$method['className'],"__construct");
            if($constructor == null){
                $txt = "\t\t\t\$".$classInstance." = new ".$method['className'].";\n";
                fwrite(self::$file, $txt);
                return $classInstance;
            }
            $parameterList = ClassDiagramService::selectParameterByMethodID(self::$diagramID,$constructor['methodID']);
            $txt = "\t\t\t\$".$classInstance." = new".$method['className']."(";
            fwrite(self::$file, $txt);
            self::setDefaultValueToParameter($parameterList);
            return $classInstance;
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
        private static function closeFile(){
            if(self::$sourceLang === "PHP"){
                fwrite(self::$file,"\t}\n?>\n");
            }else{
                fwrite(self::$file,"}");
            }
            fclose(self::$file);
            echo self::$filenameWithExtension;
        }
    }
?>