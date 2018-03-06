<?php
    require_once "ClassDiagramService.php";
    class CDProcessor{
        private static $conn;
        public static function readClassDiagram($filename,$targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                Script::consoleLog("Failed loading XML: ");
                foreach(libxml_get_errors() as $error) {
                    Script::consoleLog("<br>", $error->message);
                }
                die("XMLProcessor Terminated. Please open console to see errors");
            }
        }
        private static function saveFileToDB($fileName,$targetFile){
            self::$conn = Database::connectToDB();
            //Database::dropDatabase(self::$conn,'classDiagram');
            
        }
        private static function processSimpleCD(){

        }
        private static function processTraditionalCD(){
            
        }
    }
?>