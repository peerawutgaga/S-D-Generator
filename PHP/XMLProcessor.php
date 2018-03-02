<?php
    require_once "CallGraphService.php";
    require_once "ClassDiagramService.php";
    class XMLProcessor{
        public static function readSequenceDiagram($targetFile){
            $xml = self::readFile($targetFile);
           
        }
        public static function readClassDiagram($targetFile){
            $xml = self::readFile($targetFile);
        }
        private static function readFile($targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                Script::consoleLog("Failed loading XML: ");
                foreach(libxml_get_errors() as $error) {
                    Script::consoleLog("<br>", $error->message);
                }
                die("XMLProcessor Terminated. Please open console to see errors");
            }else{
                return $xml;
            }
        }
    }
?>