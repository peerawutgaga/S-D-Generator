<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/php/database/CallGraphService.php';
    require_once $root.'/php/utilities/Script.php';
    require_once $root.'/php/utilities/Logger.php';
    class SDProcessor{
        private static $callGraphId;
        private static $xml;
        public static function readSequenceDiagramFile($filename, $filePath){
            self::$xml = simplexml_load_file($filePath);
            if (self::$xml === false) {
                $errorMessage = "Failed loading XML: "."<br>";
                foreach(libxml_get_errors() as $error) {
                    $errorMessage = $errorMessage."\n".$error->message;
                }
                Logger::logDatabaseError("SDProcessor",$errorMessage);
                return;
            }
            self::$callGraphId = CallGraphService::insertIntoCallGraph($filename, $filePath);
            if(self::$xml['Xml_structure'] == 'simple'){
                self::processDiagram();
            }else{
               Script::alert("Traditional XML format does not support by the tool.");
            }
        }
        private static function processDiagram(){
            //Assume that the first interaction diagram is the primary diagram.
            $diagram = self::$xml->Diagrams->InteractionDiagram[0];
            $frame = self::$xml->Models->Frame[0];
            self::identifyObjectNode($diagram,$frame);
        }
        private static function identifyObjectNode($diagram, $frame){
            foreach($diagram->Shapes->children() as $objectNode){
                $objectName = $objectNode["Name"];
                $modelId = $objectNode["Model"]; // A model id is an object id in model section
                if($objectNode->getName() == "InteractionLifeLine"){
                    $baseIdentifier = $frame->xpath("./ModelChildren/InteractionLifeLine[@Id='$modelId']/BaseClassifier/Class")[0]["Name"];
                    self::insertObjectNode($modelId, $objectName, $baseIdentifier);
                }else if($objectNode->getName() == "InteractionActor"){
                    self::insertObjectNode($modelId, $objectName, "Actor");
                    
                }else if($objectNode->getName() == "InteractionOccurrence"){
                    $gateIdStr = $objectNode->GateShapeUIModelIds->Value["Value"];
                    $gateModelId = $objectNode->xpath("./Shapes/Gate[@Id=$gateIdStr]")[0]["Model"];
                    self::insertObjectNode($gateModelId, $objectName, "Ref");
                }
            }
        }
        private static function insertObjectNode($objectIdStr,$objectName,$baseIdentifier) {
            $objectId = CallGraphService::insertIntoObjectNode(self::$callGraphId, $objectName, $baseIdentifier);
            if($objectId != -1){
                CallGraphProcessingService::insertIntoProcessingObject($objectId, $objectIdStr);
            }
        }
        private static function identifyMessage(){
            
        }
    }
?>