<?php
    require_once "Database.php";
    class CallGraphService{
        private static function createGraphTable($conn){
            $createGraphTableSQL =  "CREATE TABLE IF NOT EXISTS graph(
                graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                graphName VARCHAR(30) NOT NULL,
                fileTarget VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($createGraphTableSQL) === TRUE) {
                Script::consoleLog("Graph table created successfully");
            } else {
                Script::consoleLog("Error creating graph table: " . $conn->error);
            }
        }
        private static function createNodeTable($conn){
            $createNodeTableSQL =  "CREATE TABLE IF NOT EXISTS node(
                nodeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                nodeName VARCHAR(30) NOT NULL,
                graphID INT(6) NOT NULL
            )";
            if ($conn->query($createNodeTableSQL) === TRUE) {
                Script::consoleLog("Node table created successfully");
            } else {
                Script::consoleLog("Error creating node table: " . $conn->error);
            }
        }
        private static function createMessageTable($conn){
            $createMessageTableSQL =  "CREATE TABLE IF NOT EXISTS message(
                messageID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                messageName VARCHAR(30) NOT NULL,
                sentNodeID INT(6) NOT NULL,
                receivedNodeID INT(6) NOT NULL
            )";
            if ($conn->query($createMessageTableSQL) === TRUE) {
                Script::consoleLog("Message table created successfully");
            } else {
                Script::consoleLog("Error creating message table: " . $conn->error);
            }
        } 
        public static function initialCallGraphDatabase(){
            $conn = Database::connectToDB();
            Database::createDatabaseIfNotExist($conn,'CallGraph');
            Database::selectDB($conn,'CallGraph');
            self::createGraphTable($conn);
            self::createNodeTable($conn);
            self::createMessageTable($conn);
            $conn->close();
        }
        public static function insertToGraphTable($conn, $graphName, $fileTarget){
            $insertSQL = "INSERT TO graph (graphName, fileTarget)
            VALUE ($graphName, $fileTarget)";
            if($conn->query($insertSQL) === TRUE){
                Script::consoleLog("Insert to graph table successfully");
            }else{
                Script::consoleLog("Error inserting graph table");
            }
        }
        public static function insertToNodeTable($conn, $nodeName, $graphID){
            $insertSQL = "INSERT TO node (nodeName, graphID)
            VALUE ($nodeName, $graphID)";
            if($conn->query($insertSQL) === TRUE){
                Script::consoleLog("Insert to node table successfully");
            }else{
                Script::consoleLog("Error inserting node table");
            }
        }
        public static function insertToMessageTable($conn, $messageName, $sentNodeID, $receivedNodeID){
            $insertSQL = "INSERT TO node (messageName, sentNodeID, messageNodeID)
            VALUE ($messageName, $sentNodeID, $receivedNodeID)";
            if($conn->query($insertSQL) === TRUE){
                Script::consoleLog("Insert to message table successfully");
            }else{
                Script::consoleLog("Error inserting message table");
            }
        }
    }
?>