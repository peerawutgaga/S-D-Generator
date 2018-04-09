<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/Page/PHPGenerator.php";
    require_once "$root/Page/JavaGenerator.php";
    require_once "$root/PHP/Script.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    //CreateSourceCode::initial($graphID, $diagramID, $classID, $sourceType, $sourceLang);
    class CreateSourceCode{
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
            if(self::$sourceType == "stub"){
                $stubList = self::identifyStub();
            }else{
                $driver = self::identifyDriver();
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