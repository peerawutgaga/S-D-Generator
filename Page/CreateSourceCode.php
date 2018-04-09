<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/Page/PHPGenerator.php";
    require_once "$root/Page/JavaGenerator.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang);
    class SourceCodeGenerator{
        private static $file;
        private static $graphID;
        private static $diagramID;
        private static $classID;
        private static $sourceType;
        private static $sourceLang;
        private static $root;
        public static function createSourceCode($graphID, $diagramID, $classID, $sourceType, $sourceLang){
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
            }
        }
        private static function identifyStub(){
            
        }
        private static function identifyDriver(){
                   
        } 
    }
?>