<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "SimpleCDProcessor.php";
    require_once "TraditionalCDProcessor.php";
    include_once "$root/Diagram/ClassDiagram/ClassDiagram.php";
    use ClassDiagram\ClassDiagram;
    class CDProcessor{
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
                SimpleCDProcessor::processSimpleCD($xml,self::$diagramID);
            }else{
               TraditionalCDProcessor::processTraditionalCD($xml,self::$diagramID);
            }
        }
        private static function saveFileToDB($filename,$fileTarget){
            $classDiagram = new ClassDiagram($filename);
            $classDiagram->setFileTarget($fileTarget);
            ClassDiagramService::insertToDiagramTable($classDiagram);
            self::$diagramID = ClassDiagramService::selectFromDiagramByDiagramName($filename)['diagramID'];
        }
    }
?>