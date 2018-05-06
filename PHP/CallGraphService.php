<?php
    require_once "Database.php";
    class CallGraphService{
        private static function createGraphTable($conn){
            $sql =  "CREATE TABLE IF NOT EXISTS graph(
                graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                graphName VARCHAR(100) NOT NULL,
                fileTarget VARCHAR(255) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createNodeTable($conn){
            $sql =  "CREATE TABLE IF NOT EXISTS node(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                graphID INT(6) UNSIGNED NOT NULL,
                FOREIGN KEY (graphID) REFERENCES graph(graphID) ON DELETE CASCADE,
                nodeID VARCHAR(16) NOT NULL, 
                nodeName VARCHAR(50) NOT NULL
            )";
             if ($conn->query($sql) === FALSE) {
                echo "Error at creating node table: ".$conn->error."<br>";
            }
        }
        private static function createMessageTable($conn){
            $sql =  "CREATE TABLE IF NOT EXISTS message(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                graphID INT(6) UNSIGNED NOT NULL,
                FOREIGN KEY (graphID) REFERENCES graph(graphID) ON DELETE CASCADE,
                messageID VARCHAR(16)NOT NULL, 
                messageName VARCHAR(50) NOT NULL,
                sentNodeID VARCHAR(16) NOT NULL,
                receivedNodeID VARCHAR(16) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating message table: ".$conn->error."<br>";
            }
        } 
        private static function addUniqueKey($conn){
            $uniqueNode = "ALTER TABLE node ADD CONSTRAINT nodeIdx UNIQUE INDEX (graphID, nodeID)";
            $conn->query($uniqueNode);
            $uniqueMessage = "ALTER TABLE message ADD CONSTRAINT messageIdx UNIQUE INDEX (graphID, messageID)";
            $conn->query($uniqueMessage);
        }
        public static function initialCallGraphDatabase($conn){
            Database::createDatabaseIfNotExist($conn,'CallGraph');
            Database::selectDB($conn,'CallGraph');
            self::createGraphTable($conn);
            self::createNodeTable($conn);
            self::createMessageTable($conn);
            self::addUniqueKey($conn);
        }
        public static function insertToGraphTable($conn, $graphName, $fileTarget){
           $sql = $conn->prepare("INSERT INTO graph(graphName, fileTarget) VALUES(?,?)");
           $sql->bind_param("ss",$graphName,$fileTarget);
           if($sql->execute()===FALSE){
                echo "Error at inserting to graph table: ".$sql->error."<br>";
           }
           $sql->close();
        }
        public static function insertToNodeTable($conn, $graphID, $nodeID, $nodeName){
            $sql = $conn->prepare("INSERT INTO node(graphID, nodeID, nodeName) VALUES(?,?,?)");
            $sql->bind_param("iss",$graphID,$nodeID, $nodeName);
            if($sql->execute()===FALSE){
                 echo "Error at inserting to node table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToMessageTable($conn, $graphID, $messageID, $messageName, $sentNodeID, $receivedNodeID){
            $sql = $conn->prepare("INSERT INTO message(graphID, messageID, messageName, sentNodeID, receivedNodeID) 
                VALUES(?,?,?,?,?)");
            $sql->bind_param("issss",$graphID, $messageID, $messageName, $sentNodeID, $receivedNodeID);
            if($sql->execute()===FALSE){
                 echo "Error at inserting to message table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function selectFromGraphTable($value,$field,$keyword){
            $conn = Database::connectToDBUsingPDO('callGraph');
            if($field == 'graphID'){
                $sql = $conn->prepare("SELECT * FROM graph WHERE graphID = :keyword LIMIT 1");
            }else if($field == 'graphName'){
                $sql = $conn->prepare("SELECT * FROM graph WHERE graphName = :keyword LIMIT 1");
            }
            $sql->bindParam(':keyword',$keyword);
            $sql->execute();
            $result = $sql->fetch();
            return $result[$value];
        }
        public static function selectAllFromGraph(){
            $conn = Database::connectToDBUsingPDO('callGraph');
            $sql = $conn->prepare("SELECT * FROM graph");
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectAllFromNode($graphID){
            $conn = Database::connectToDBUsingPDO('callGraph');
            $sql = $conn->prepare("SELECT * FROM node WHERE graphID = :graphID");
            $sql->bindParam(':graphID',$graphID);
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectMessageBySentNodeID($graphID, $sentNodeID){
            $conn = Database::connectToDBUsingPDO('callGraph');
            $sql = $conn->prepare("SELECT * FROM message WHERE graphID = :graphID AND sentNodeID = :sentNodeID");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':sentNodeID',$sentNodeID);
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectMessageByReceivedNodeID($graphID, $receivedNodeID){
            $conn = Database::connectToDBUsingPDO('callGraph');
            $sql = $conn->prepare("SELECT * FROM message WHERE graphID = :graphID AND receivedNodeID = :receivedNodeID");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':receivedNodeID',$receivedNodeID);
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectNodeByNodeID($graphID, $nodeID){
            $conn = Database::connectToDBUsingPDO('callGraph');
            $sql = $conn->prepare("SELECT * FROM node WHERE graphID = :graphID AND nodeID = :nodeID LIMIT 1");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':nodeID',$nodeID);
            $sql->execute();
            $result = $sql->fetch();
            return $result;
        }
    }

?>