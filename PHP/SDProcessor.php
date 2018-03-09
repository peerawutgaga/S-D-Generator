<?php
    require_once "CallGraphService.php";
    class SDProcessor{
        private static $conn;
        private static $graphID;
        public static function readSequenceDiagram($fileName, $targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                echo "Failed loading XML: "."<br>";
                foreach(libxml_get_errors() as $error) {
                    echo "<br>", $error->message;
                }
                die("XMLProcessor Terminated.");
            }
            self::saveFileToDB($fileName,$targetFile);
            if($xml['Xml_structure'] == 'simple'){
                self::processSimpleSD($xml);
            }else{
                self::processTraditionalSD($xml);
            }
        }
        private static function saveFileToDB($fileName,$targetFile){
            self::$conn = Database::connectToDB();
            // Database::dropDatabase(self::$conn,'callGraph');
            // CallGraphService::initialCallGraphDatabase(self::$conn);
            Database::selectDB(self::$conn,'callGraph');
            CallGraphService::insertToGraphTable(self::$conn, $fileName, $targetFile);
            self::$graphID = CallGraphService::selectFromGraphTable('graphID','graphName',$fileName);
        }
        private static function processSimpleSD($xml){
            $nodeList = $xml->Models->Frame->ModelChildren;
            $messageList = $xml->Models->ModelRelationshipContainer->ModelChildren->ModelRelationshipContainer->ModelChildren;
            $connectorList = $xml->Diagrams->InteractionDiagram->Connectors;
            self::identifyNodeSimple($nodeList);
            self::identifyMessageSimple($messageList,$connectorList);
            self::$conn->close();
        }
        private static function identifyNodeSimple($nodeList){
            $nodeID; $nodeName;
            foreach($nodeList->children() as $node){
                if($node->getName() == 'InteractionLifeLine'){
                    $nodeName = $node['BaseClassifier'];
                    $nodeID = $node->MasterView->InteractionLifeLine['Idref'];
                    CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
                }else if($node->getName() == 'InteractionActor'){
                    $nodeName = $node['Name'];
                    $nodeID = $node->MasterView->InteractionActor['Idref'];
                    CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
                }
            }
        }
        private static function identifyMessageSimple($messageList,$connectorList){
            $messageID; $messageName; $sentNodeID; $receivedNodeID;
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
                    CallGraphService::insertToMessageTable(self::$conn, self::$graphID,$messageID, $messageName,$sentNodeID, $receivedNodeID);
                }
            }
        }
        private static function processTraditionalSD($xml){
            $nodeList = $xml->Models->Model[11]->ChildModels;
            $messageList = $xml->Models->Model[10]->ChildModels->Model->ChildModels;
            self::identifyNodeTraditional($nodeList);
            self::identifyMessageTraditional($messageList);
            self::$conn->close();
        }
        private static function identifyNodeTraditional($nodeList){
            $nodeID; $nodeName;
            foreach($nodeList->children() as $node){
                $nodeID = $node['id'];
                if($node['modelType'] == "InteractionActor"){
                    $nodeName = $node['name'];
                }else{
                    $nodeName = $node->ModelProperties->TextModelProperty->StringValue['value'];
                }
                CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
            }
        }
        private static function identifyMessageTraditional($messageList){
            $messageID; $messageName; $sentNodeID; $receivedNodeID;
            foreach($messageList->children() as $message){
                if(self::isReturn($message)==false){
                    $messageID = $message['id'];
                    $messageName = $message['name'];
                    $sentNodeID = $message->FromEnd->Model->ModelProperties->ModelRefProperty->ModelRef['id'];
                    $receivedNodeID = $message->ToEnd->Model->ModelProperties->ModelRefProperty->ModelRef['id'];
                    CallGraphService::insertToMessageTable(self::$conn, self::$graphID,$messageID, $messageName,$sentNodeID, $receivedNodeID);
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