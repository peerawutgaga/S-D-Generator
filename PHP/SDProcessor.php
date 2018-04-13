<?php
    require_once "CallGraphService.php";
    class SDProcessor{
        private static $conn;
        private static $graphID;
        private static $classRef;
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
        private static function processTraditionalSD($xml){
            $idx = 0;
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
            self::$conn->close();
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