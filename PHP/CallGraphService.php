<?php
    require "Database.php";
    function createGraphTable($conn){
        $createGraphTableSQL =  "CREATE TABLE IF NOT EXISTS graph(
            graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            graphName VARCHAR(30) NOT NULL,
            fileTarget VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createGraphTableSQL) === TRUE) {
            consoleLog("Graph table created successfully");
        } else {
            consoleLog("Error creating graph table: " . $conn->error);
        }
    }
    function createNodeTable($conn){
        $createNodeTableSQL =  "CREATE TABLE IF NOT EXISTS node(
            nodeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            nodeName VARCHAR(30) NOT NULL,
            graphID INT(6) NOT NULL
        )";
        if ($conn->query($createNodeTableSQL) === TRUE) {
            consoleLog("Node table created successfully");
        } else {
            consoleLog("Error creating node table: " . $conn->error);
        }
    }
    function createMessageTable($conn){
        $createMessageTableSQL =  "CREATE TABLE IF NOT EXISTS message(
            messageID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            messageName VARCHAR(30) NOT NULL,
            sentNodeID INT(6) NOT NULL,
            receivedNodeID INT(6) NOT NULL
        )";
        if ($conn->query($createMessageTableSQL) === TRUE) {
            consoleLog("Message table created successfully");
        } else {
            consoleLog("Error creating message table: " . $conn->error);
        }
    } 
    function initialCallGraphDatabase($conn){
        createDatabaseIfNotExist($conn,'CallGraph');
        selectDB($conn,'CallGraph');
        createGraphTable($conn);
        createNodeTable($conn);
        createMessageTable($conn);
    }
    function insertToGraphTable($conn, $graphName, $fileTarget){
        $insertSQL = "INSERT TO graph (graphName, fileTarget)
        VALUE ($graphName, $fileTarget)";
        if($conn->query($insertSQL) === TRUE){
            consoleLog("Insert to graph table successfully");
        }else{
            consoleLog("Error inserting graph table");
        }
    }
    function insertToNodeTable($conn, $nodeName, $graphID){
        $insertSQL = "INSERT TO node (nodeName, graphID)
        VALUE ($nodeName, $graphID)";
        if($conn->query($insertSQL) === TRUE){
            consoleLog("Insert to node table successfully");
        }else{
            consoleLog("Error inserting node table");
        }
    }
    function insertToMessageTable($conn, $messageName, $sentNodeID, $receivedNodeID){
        $insertSQL = "INSERT TO node (messageName, sentNodeID, messageNodeID)
        VALUE ($messageName, $sentNodeID, $receivedNodeID)";
        if($conn->query($insertSQL) === TRUE){
            consoleLog("Insert to message table successfully");
        }else{
            consoleLog("Error inserting message table");
        }
    }
?>