<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    class SimpleSDProcessor{
        private static $conn;
        private static $graphID;
        public static function processSimpleSD($xml,$conn,$graphID){
            self::$conn = $conn;
            self::$graphID = $graphID;
            $nodeList = $xml->Models->Frame->ModelChildren;
            $messageList = $xml->Models->ModelRelationshipContainer->ModelChildren->ModelRelationshipContainer->ModelChildren;
            $connectorList = $xml->Diagrams->InteractionDiagram->Connectors;
            self::identifyNodeSimple($nodeList);
            self::identifyMessageSimple($messageList,$connectorList);
            self::$conn->close();
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
                    CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
                }else if($node->getName() == 'InteractionActor'){
                    $nodeName = $node['Name'];
                    $nodeID = $node->MasterView->InteractionActor['Idref'];
                    CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
                }
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
                        CallGraphService::insertToMessageTable(self::$conn, self::$graphID,$messageID, $messageName,$sentNodeID, $receivedNodeID);
                    }
                }
            }
        }
    }
?>