<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/Database/CallGraphService.php";
    include_once "$root/Diagram/SequenceDiagram/ObjectNode.php";
    include_once "$root/Diagram/SequenceDiagram/Message.php";
    include_once "$root/Diagram/SequenceDiagram/Argument.php";
    use SequenceDiagram\ObjectNode;
    use SequenceDiagram\Message;
    use SequenceDiagram\Argument;
    class SimpleSDProcessor{
        private static $graphID;
        public static function processSimpleSD($xml,$graphID){
            self::$graphID = $graphID;
            $nodeList = $xml->Models->Frame->ModelChildren;
            $messageList = $xml->Models->ModelRelationshipContainer->ModelChildren->ModelRelationshipContainer->ModelChildren;
            $connectorList = $xml->Diagrams->InteractionDiagram->Connectors;
            self::identifyNodeSimple($nodeList);
            self::identifyMessageSimple($messageList,$connectorList);
        }
        private static function identifyNodeSimple($nodeList){
            foreach($nodeList->children() as $node){
                if($node->getName() == 'InteractionLifeLine'){
                    if(isset($node['BaseClassifier'])){
                        $nodeName = $node['BaseClassifier'];
                        $nodeID = $node->MasterView->InteractionLifeLine['Idref'];
                    }else{
                        $nodeName = $node->BaseClassifier->Class['Name'];
                        $nodeID = $node->BaseClassifier->Class['Idref'];
                    }
                }else if($node->getName() == 'InteractionActor'){
                    $nodeName = $node['Name'];
                    $nodeID = $node->MasterView->InteractionActor['Idref'];
                }
                $objectNode = new ObjectNode($nodeID,$nodeName);
                CallGraphService::insertToNodeTable(self::$graphID,$objectNode);
            }
        }
        private static function identifyMessageSimple($messageList,$connectorList){
            foreach($messageList->children() as $message){
                if($message->ActionType->ActionTypeReturn == null){
                    $messageID = $message->MasterView->Message['Idref'];
                    $messageName = $message['Name'];
                    foreach($connectorList->children() as $connector){
                        if(strcmp($connector['Id'],$messageID)==0){
                            $sentNodeID = $connector['From']; 
                            $receivedNodeID = $connector['To'];
                            break;
                        }
                    }
                    if(strcmp($sentNodeID,$receivedNodeID) !== 0){
                        $messageObject = new Message($messageID,$messageName);
                        $messageObject->setSentNodeID($sentNodeID);
                        $messageObject->setReceivedNodeID($receivedNodeID);
                        CallGraphService::insertToMessageTable(self::$graphID,$messageObject);
                        self::identifyArgumentSimple($message);
                    }
                }
            }
        }
        private static function identifyArgumentSimple($message){
            if($message->Arguments != null){
                $messageID = $message->MasterView->Message['Idref'];
                foreach($message->Arguments->children() as $argument){
                    $argumentObject = new Argument($argument["Id"],$argument["Value"]);
                    CallGraphService::insertToArgumentTable(self::$graphID,$messageID,$argumentObject);
                }
            }
        }
        //TODO Guard condition identify
        //TODO Reference Diagram linking
    }
?>