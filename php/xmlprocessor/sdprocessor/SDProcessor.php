<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root."/php/xmlprocessor/sdprocessor/SimpleSDProcessor.php";
    require_once $root.'/php/database/CallGraphService.php';
    require_once $root.'/php/utilities/Script.php';
    require_once $root.'/php/utilities/Logger.php';
    class SDProcessor{
        private static $callGraphId;
        public static function readSequenceDiagramFile($filename, $filePath){
            $xml = simplexml_load_file($filePath);
            if ($xml === false) {
                $errorMessage = "Failed loading XML: "."<br>";
                foreach(libxml_get_errors() as $error) {
                    $errorMessage = $errorMessage."\n".$error->message;
                }
                Logger::logDatabaseError("SDProcessor",$errorMessage);
            }
            self::$callGraphId = CallGraphService::insertIntoCallGraph($filename, $filePath);
            if($xml['Xml_structure'] == 'simple'){
                SimpleSDProcessor::processSimpleSD($xml,self::$callGraphId);
            }else{
                
               Script::alert("Traditional XML format does not support.");
            }
        }
    }
?>