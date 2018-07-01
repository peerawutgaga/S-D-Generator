<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    class TraditionalSDProcessor{
        private static $conn;
        private static $graphID;
        private static $classRef;
        public static function processTraditionalSD($xml,$conn,$graphID){
            $idx = 0;
            self::$conn = $conn;
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
                CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
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
                        CallGraphService::insertToMessageTable(self::$conn, self::$graphID,$messageID, $messageName,$sentNodeID, $receivedNodeID);
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