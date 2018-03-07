<?php
    require_once "ClassDiagramService.php";
    class CDProcessor{
        private static $conn;
        private static $diagramID;
        public static function readClassDiagram($filename,$targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                Script::consoleLog("Failed loading XML: ");
                foreach(libxml_get_errors() as $error) {
                    Script::consoleLog("<br>", $error->message);
                }
                die("XMLProcessor Terminated. Please open console to see errors");
            }
            self::saveFileToDB($fileName,$targetFile);
            if($xml['Xml_structure'] == 'simple'){
                self::processSimpleCD($xml);
            }else{
                self::processTraditionalCD($xml);
            }
        }
        private static function saveFileToDB($fileName,$fileTarget){
            self::$conn = Database::connectToDB();
            //Database::dropDatabase(self::$conn,'classDiagram');
            ClassDiagramService::initialClassDiagramDatabase(self::$conn, $fileName, $fileTarget);
            self::$diagramID = ClassDiagramService::selectFromDiagramTable('diagramID','diagramName',$fileName);
        }
        private static function processSimpleCD($xml){
            $classList = $xml->Models->Package->ModelChildren->Package->ModelChildren;
            
        }
        private static function processTraditionalCD($xml){
            
        }
    }
?>