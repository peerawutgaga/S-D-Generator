<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/Page/SourceCodeGen/PHPGenerator.php";
    require_once "$root/Page/SourceCodeGen/JavaGenerator.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::initial($graphID, $diagramID, $classID, $sourceType, $sourceLang);
    class SourceCodeGenerator{
        private static $file;
        private static $graphID;
        private static $diagramID;
        private static $classID;
        private static $sourceType;
        private static $sourceLang;
        private static $root;
        public static function initial($graphID, $diagramID, $classID, $sourceType, $sourceLang){
            self::$graphID = $graphID;
            self::$diagramID = $diagramID;
            self::$classID = $classID;
            self::$sourceType = $sourceType;
            self::$sourceLang = $sourceLang;
            self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            SourceCodeService::initialSourceCodeDatabase();
            if(!self::checkIfMessagesAreEmpty()){
                self::createSourceCode();
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
        private static function createSourceCode(){
            if(self::$sourceType == "stub"){
                $stubList = self::identifyStub();
                $ait = new ArrayIterator($stubList);
                $cit = new CachingIterator($ait); 
                $fileList = "";    
                if(self::$sourceLang=="Java"){
                    foreach($cit as $stub){
                        $fileList = $fileList.JavaGenerator::createStub($stub);
                        if($cit->hasNext()){
                            $fileList = $fileList.",";
                        }
                    }
                }else{
                    foreach($cit as $stub){
                        $fileList = $fileList.PHPGenerator::createStub($stub);
                        if($cit->hasNext()){
                            $fileList = $fileList.",";
                        }
                    }
                }
                echo $fileList;
            }else{
                $driver = self::identifyDriver();
                if(self::$sourceLang == "Java"){
                    $filename = JavaGenerator::createDriver($driver);
                }else{
                    $filename = PHPGenerator::createDriver($driver);
                }
                echo $filename;
            }
        }
        private static function identifyStub(){
            $messageList = CallGraphService::selectMessageBySentNodeID(self::$graphID,self::$classID);
            $stubList  = array();
            foreach($messageList as $message){
                $node = CallGraphService::selectNodeByNodeID(self::$graphID,$message['receivedNodeID']);
                $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID,$node['nodeName']);
                array_push($stubList, $class);
            }
            $stubList = array_unique($stubList,SORT_REGULAR);
            return $stubList;
        }
        private static function identifyDriver(){
            $node = CallGraphService::selectNodeByNodeID(self::$graphID,self::$classID);
            $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID,$node['nodeName']);
            return $class;
        }
    }
?>