<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/Database/CallGraphService.php";
    use SequenceDiagram\ObjectNode;
    use SequenceDiagram\Message;
    use SequenceDiagram\Argument;
    class TraditionalSDProcessor{
        private static $graphID;
        private static $classRef;
        public static function processTraditionalSD($xml,$graphID){
            $idx = 0;
            self::$graphID = $graphID;
            self::$classRef = array();
            foreach($xml->Models->Model as $model){
                if($model['displayModelType'] == "ModelRelationshipContainer"){
                    $messageList = $xml->Models->Model[$idx]->ChildModels->Model->ChildModels;
                }
                if($model['displayModelType'] == "Class"){
                    $class = $xml->Models->Model[$idx];
                    self::$classRef[(string) $class['id']] = (string) $class['name'];
                }
                if($model['displayModelType'] == "Frame"){
                    $nodeList = $xml->Models->Model[$idx]->ChildModels;
                }
                $idx = $idx + 1;
            }
            self::identifyNodeTraditional($nodeList);
            self::identifyMessageTraditional($messageList);
        }
        private static function identifyNodeTraditional($nodeList){
            foreach($nodeList->children() as $node){
                $nodeID = $node['id'];
                if($node['modelType'] == "InteractionActor"){
                    $nodeName = $node['name'];
                }else{
                    $nodeName = $node->ModelProperties->TextModelProperty->StringValue['value'];
                    if(!isset($nodeName)){
                        $id = $node->ModelProperties->TextModelProperty->ModelRef['id'];
                        $nodeName = self::$classRef[(string) $id];
                    }
                }
                $objectNode = new ObjectNode($nodeID, $nodeName);
                CallGraphService::insertToNodeTable(self::$graphID, $objectNode);
            }
        }
        private static function identifyMessageTraditional($messageList){
            foreach($messageList->children() as $message){
                if(self::isReturn($message)==false){
                    $messageID = $message['id'];
                    $messageName = $message['name'];
                    $sentNodeID = $message->FromEnd->Model->ModelProperties->ModelRefProperty->ModelRef['id'];
                    $receivedNodeID = $message->ToEnd->Model->ModelProperties->ModelRefProperty->ModelRef['id'];
                    if(strcmp($sentNodeID,$receivedNodeID) !== 0){
                        $messageObject = new Message($messageID,$messageName);
                        $messageObject->setSentNodeID($sentNodeID);
                        $messageObject->setReceivedNodeID($receivedNodeID);
                        CallGraphService::insertToMessageTable(self::$graphID,$messageObject);
                        self::identifyArgumentTraditional($message);
                    }
                }
            }
        }
        private static function identifyArgumentTraditional($message){
            foreach($message->ModelProperties->children() as $property){
                if(strcmp($property['name'],"arguments")==0){
                    foreach($property->children() as $arguementModel){
                        $argID = $arguementModel["id"];
                        foreach($arguementModel->ModelProperties->children() as $arguementModelProperty){
                            if(strcmp($arguementModelProperty['name'],"value")==0){
                                $argName = $arguementModelProperty->StringValue["value"];
                                $argumentObject = new Argument($argID,$argName);
                                CallGraphService::insertToArgumentTable(self::$graphID,$message['id'],$argumentObject);
                            }
                        }
                    }
                }
            }
        }
        private static function isReturn($message){
            foreach($message->ModelProperties->children() as $property){
                if(strcmp($property['name'],"actionType")==0){
                    if($property->Model['name']=='Return'){
                        return true;
                    }
                }
            }
            return false;
        }
    }
?>