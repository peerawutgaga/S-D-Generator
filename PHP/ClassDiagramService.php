<?php
    require_once "Database.php";
    function createDiagramTable($conn){
        $createDiagramTableSQL =  "CREATE TABLE IF NOT EXISTS diagram(
            diagramID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            diagramName VARCHAR(30) NOT NULL,
            fileName VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createDiagramTableSQL) === TRUE) {
            consoleLog( "Diagram table created successfully");
        } else {
            consoleLog( "Error creating diagram table: " . $conn->error);
        }
    }
    function createClassTable($conn){
        $createClassTableSQL = "CREATE TABLE IF NOT EXISTS class(
            classID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            className VARCHAR(30) NOT NULL,
            diagramID INT(6) NOT NULL
        )";
        if ($conn->query($createClassTableSQL) === TRUE) {
            consoleLog( "Class table created successfully");
        } else {
            consoleLog( "Error creating class table: " . $conn->error);
        }
    }
    function createMethodTable($conn){
        $createMethodTableSQL = "CREATE TABLE IF NOT EXISTS method(
            methodID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            methodName VARCHAR(50) NOT NULL,
            parameterCount INT NOT NULL,
            returnType VARCHAR(30) NOT NULL,
            classID INT(6) NOT NULL
        )";
        if ($conn->query($createMethodTableSQL) === TRUE) {
            consoleLog( "Method table created successfully");
        } else {
            consoleLog( "Error creating method table: " . $conn->error);
        }
    }
    function createParameterTable($conn){
        $createParameterTableSQL = "CREATE TABLE IF NOT EXISTS parameter(
            parameterID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            parameterName VARCHAR(30) NOT NULL,
            parameterType VARCHAR(30) NOT NULL,
            methodID INT(6) NOT NULL
        )";
        if ($conn->query($createParameterTableSQL) === TRUE) {
            consoleLog( "Parameter table created successfully");
        } else {
            consoleLog( "Error creating parameter table: " . $conn->error);
        }
    }
    function initialClassDiagramDatabase($conn){
        createDatabaseIfNotExist($conn,'ClassDiagram');
        selectDB($conn,'ClassDiagram');
        createDiagramTable($conn);
        createClassTable($conn);
        createMethodTable($conn);
        createParameterTable($conn);
    }
?>