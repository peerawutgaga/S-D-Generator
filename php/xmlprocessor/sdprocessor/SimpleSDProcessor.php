<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/php/database/CallGraphService.php";
    require_once "$root/php/database/CallGraphProcessingService.php";
    require_once "$root/php/utilities/Script.php";
    class SimpleSDProcessor{
        private static $graphID;
        public static function processSimpleSD($xml,$graphID){
            self::$graphID = $graphID;
            $objectList = $xml->Models->Frame->ModelChildren;
            $messageList = $xml->xpath("//Message");
            self::identifyNode($objectList);
            self::identifyMessage($xml,$messageList);
        }
        private static function identifyNode($objectList){
            
            foreach($objectList->children() as $objectNode){
                if($objectNode->getName() == 'InteractionLifeLine'){
                    $objectName = $objectNode['Name'];
                    $baseIdentifier = $objectNode->BaseClassifier->Class['Name'];
                    $objectId = CallGraphService::insertIntoObjectNode(self::$graphID,$objectName,$baseIdentifier);
                    if($objectId != null){
                        CallGraphProcessingService::insertIntoProcessingObject($objectId, $objectNode['Id']);
                    }
                }else if($objectNode->getName() == 'InteractionActor'){
                    $objectName = $objectNode['Name'];
                    $objectId = CallGraphService::insertIntoObjectNode(self::$graphID,$objectName,"Actor");
                    if($objectId != null){
                        CallGraphProcessingService::insertIntoProcessingObject($objectId, $objectNode['Id']);
                    }
                }
            }
        }
        private static function identifyMessage($xml,$messageList){
            foreach($messageList as $message){
               $actionType = $message->ActionType->children()[0]["Name"];
               $fromObjectIdStr = $message->FromEnd->MessageEnd["EndModelElement"];
               $fromObjectId = CallGraphProcessingService::selectObjectIdByObjectIdStr($fromObjectIdStr);
               $toObjectIdStr = $message->ToEnd->MessageEnd["EndModelElement"];
               $toObjectId = CallGraphProcessingService::selectObjectIdByObjectIdStr($toObjectIdStr);
               if($fromObjectId == null || $toObjectId == null){
                   Script::alert("Invalid XML file");
                   CallGraphService::deleteFromGraphByCallGraphId(self::$graphID);
                   return;
               }
            }
        }
        private static function handleReturnMessage(){
            
        }
        private static function identifyArgument($message){
            
        }
        //TODO Guard condition identify
        //TODO Reference Diagram linking
    }
?>