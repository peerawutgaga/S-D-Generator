<?php
     require_once "SimpleCDProcessor.php";
     require_once "TraditionalCDProcessor.php";
     include "$Diagram/ClassDiagram.php";
     use ClassDiagram\ClassDiagram;
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
            self::saveFileToDB($filename,$targetFile);
            if($xml['Xml_structure'] == 'simple'){
                SimpleCDProcessor::processSimpleCD($xml,self::$conn,self::$diagramID);
            }else{
               TraditionalCDProcessor::processTraditionalCD($xml,self::$conn,self::$diagramID);
            }
        }
        private static function saveFileToDB($filename,$fileTarget){
            // Database::dropDatabase(self::$conn,'classDiagram');
            // ClassDiagramService::initialClassDiagramDatabase(self::$conn, $filename, $fileTarget);
            $classDiagram = new ClassDiagram($filename, $fileTarget);
            ClassDiagramService::insertToDiagramTable($filename, $fileTarget);
            self::$diagramID = ClassDiagramService::selectFromDiagramByDiagramName($filename)['diagramID'];
        }
    }
?>