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
            Database::dropDatabase(self::$conn,'callGraph');
            CallGraphService::initialCallGraphDatabase(self::$conn);
            CallGraphService::insertToGraphTable(self::$conn, $fileName, $targetFile);
            self::$graphID = CallGraphService::selectFromGraphTable('graphID','graphName',$fileName);
        }
        private static function processSimpleSD($xml){
            // print_r($xml);
            $nodeList = $xml->Models->Frame->ModelChildren;
            self::identifyNodeSimple($nodeList);
            self::$conn->close();
        }
        private static function processTraditionalSD($xml){
            
        }
        private static function identifyNodeSimple($nodeList){
            $nodeID;
            $nodeName;
            foreach($nodeList->children() as $node){
                if($node->getName() == 'InteractionLifeLine'){
                    $nodeName = $node['BaseClassifier'];
                    $nodeID = $node->MasterView->InteractionLifeLine['Idref'];
                }else if($node->getName() == 'InteractionActor'){
                    $nodeName = $node['Name'];
                    $nodeID = $node->MasterView->InteractionActor['Idref'];
                }
                CallGraphService::insertToNodeTable(self::$conn,self::$graphID,$nodeID,$nodeName);
            }
        }
        private static function identifyMessageSimple($messageList){
            
        }
    }
?>