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
    function createIfNotExist($conn, $db_name){
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }
    }
    function createCallGraphTable($conn){
        $createGraphTableSQL =  "CREATE TABLE IF NOT EXISTS graphTable(
            graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            graphName VARCHAR(30) NOT NULL,
            createDate TIMESTAMP
        )";
        $createNodeTableSQL =  "CREATE TABLE IF NOT EXISTS nodeTable(
            nodeID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            nodeName VARCHAR(30) NOT NULL,
            graphID INT(6) NOT NULL
        )";
        $createMessageTableSQL =  "CREATE TABLE IF NOT EXISTS messageTable(
            messageID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            messageName VARCHAR(30) NOT NULL,
            sentNodeID INT(6) NOT NULL,
            receivedNodeID INT(6) NOT NULL
        )";
        if ($conn->query($createGraphTableSQL) === TRUE) {
            echo "Graph table created successfully";
        } else {
            echo "Error creating graph table: " . $conn->error;
        }
        if ($conn->query($createNodeTableSQL) === TRUE) {
            echo "Node table created successfully";
        } else {
            echo "Error creating node table: " . $conn->error;
        }
        if ($conn->query($createMessageTableSQL) === TRUE) {
            echo "Message table created successfully";
        } else {
            echo "Error creating message table: " . $conn->error;
        }
    }
    function createClassDiagramTable($conn){
        $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
            fileID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            fileName VARCHAR(30) NOT NULL,
            fileLocation VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createFileTableSQL) === TRUE) {
            echo "Class diagram table created successfully";
        } else {
            echo "Error creating class diagram table: " . $conn->error;
        }
    }
    function createSourceCodeTable($conn){
        $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
            fileID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            fileName VARCHAR(30) NOT NULL,
            fileType VARCHAR(4) NOT NULL,
            fileLocation VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createFileTableSQL) === TRUE) {
            echo "Source code table created successfully";
        } else {
            echo "Error creating source code table: " . $conn->error;
        }
    }
    function testFunc(){
        $conn = connectToDB();
        createIfNotExist($conn,'ClassDiagram');
        $success = selectDB($conn,'ClassDiagram');
        echo "$success";
        createClassDiagramTable($conn);
        $conn->close();
    }



?>