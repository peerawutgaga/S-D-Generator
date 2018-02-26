<?php
    
    function connectToDB(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        return $conn;
    }
    function selectDB($conn,$db_name){
        $db_selected = mysqli_select_db($conn, $db_name);
        if (!$db_selected) {
            die ('Cannot select database: ' . mysql_error());
        }
        return $db_selected;
    }
    function createIfNotExist($db_name,$conn){
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }
    }
    function createCallGraphTable($conn){
        $createGraphTable =  "CREATE TABLE IF NOT EXISTS graphTable(
            graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            graphName VARCHAR(30) NOT NULL,
            createDate TIMESTAMP
        )";
        $createNodeTable =  "CREATE TABLE IF NOT EXISTS nodeTable(
            nodeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            nodeName VARCHAR(30) NOT NULL,
            graphID INT(6) NOT NULL
        )";
        $createMessageTable =  "CREATE TABLE IF NOT EXISTS messageTable(
            messageID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            messageName VARCHAR(30) NOT NULL,
            sentNodeID INT(6) NOT NULL,
            receivedNodeID INT(6) NOT NULL
        )";
        if ($conn->query($createGraphTable) === TRUE) {
            echo "Graph table created successfully";
        } else {
            echo "Error creating graph table: " . $conn->error;
        }
        if ($conn->query($createNodeTable) === TRUE) {
            echo "Node table created successfully";
        } else {
            echo "Error creating node table: " . $conn->error;
        }
        if ($conn->query($createMessageTable) === TRUE) {
            echo "Message table created successfully";
        } else {
            echo "Error creating message table: " . $conn->error;
        }
        function initialDiagramFileDB($conn){

        }
    }



?>