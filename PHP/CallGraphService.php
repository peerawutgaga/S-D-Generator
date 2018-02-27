<?php
    require "Database.php";
    function createGraphTable($conn){
        $createGraphTableSQL =  "CREATE TABLE IF NOT EXISTS graph(
            graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            graphName VARCHAR(30) NOT NULL,
            fileName VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createGraphTableSQL) === TRUE) {
            echo "Graph table created successfully";
        } else {
            echo "Error creating graph table: " . $conn->error;
        }
    }
    function createNodeTable($conn){
        $createNodeTableSQL =  "CREATE TABLE IF NOT EXISTS node(
            nodeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            nodeName VARCHAR(30) NOT NULL,
            graphID INT(6) NOT NULL
        )";
        if ($conn->query($createNodeTableSQL) === TRUE) {
            echo "Node table created successfully";
        } else {
            echo "Error creating node table: " . $conn->error;
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
            echo "Message table created successfully";
        } else {
            echo "Error creating message table: " . $conn->error;
        }
    } 
    function initialCallGraphDatabase($conn){
        createDatabaseIfNotExist($conn,'CallGraph');
        selectDB($conn,'CallGraph');
        createGraphTable($conn);
        createNodeTable($conn);
        createMessageTable($conn);
    }
?>