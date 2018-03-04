<?php
    require_once "Database.php";
    class ClassDiagramService{
        private static function createDiagramTable($conn){
            $sql =  "CREATE TABLE IF NOT EXISTS diagram(
                diagramID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramName VARCHAR(30) NOT NULL,
                fileName VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createClassTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS class(
                classID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                className VARCHAR(30) NOT NULL,
                diagramID INT(6) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createMethodTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS method(
                methodID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                methodName VARCHAR(50) NOT NULL,
                parameterCount INT NOT NULL,
                returnType VARCHAR(30) NOT NULL,
                classID INT(6) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createParameterTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS parameter(
                parameterID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                parameterName VARCHAR(30) NOT NULL,
                parameterType VARCHAR(30) NOT NULL,
                methodID INT(6) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        public static function initialClassDiagramDatabase($conn){
            Database::createDatabaseIfNotExist($conn,'ClassDiagram');
            Database::selectDB($conn,'ClassDiagram');
            self::createDiagramTable($conn);
            self::createClassTable($conn);
            self::createMethodTable($conn);
            self::createParameterTable($conn);
        }
    }
?>