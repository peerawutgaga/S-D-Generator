<?php
    require_once "Database.php";
    class ClassDiagramService{
        private static function createDiagramTable($conn){
            $createDiagramTableSQL =  "CREATE TABLE IF NOT EXISTS diagram(
                diagramID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramName VARCHAR(30) NOT NULL,
                fileName VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($createDiagramTableSQL)) {
                Script::consoleLog( "Diagram table created successfully");
            } else {
                Script::consoleLog( "Error creating diagram table: " . $conn->error);
            }
        }
        private static function createClassTable($conn){
            $createClassTableSQL = "CREATE TABLE IF NOT EXISTS class(
                classID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                className VARCHAR(30) NOT NULL,
                diagramID INT(6) NOT NULL
            )";
            if ($conn->query($createClassTableSQL) === TRUE) {
                Script::consoleLog( "Class table created successfully");
            } else {
                Script::consoleLog( "Error creating class table: " . $conn->error);
            }
        }
        private static function createMethodTable($conn){
            $createMethodTableSQL = "CREATE TABLE IF NOT EXISTS method(
                methodID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                methodName VARCHAR(50) NOT NULL,
                parameterCount INT NOT NULL,
                returnType VARCHAR(30) NOT NULL,
                classID INT(6) NOT NULL
            )";
            if ($conn->query($createMethodTableSQL) === TRUE) {
                Script::consoleLog( "Method table created successfully");
            } else {
                Script::consoleLog( "Error creating method table: " . $conn->error);
            }
        }
        private static function createParameterTable($conn){
            $createParameterTableSQL = "CREATE TABLE IF NOT EXISTS parameter(
                parameterID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                parameterName VARCHAR(30) NOT NULL,
                parameterType VARCHAR(30) NOT NULL,
                methodID INT(6) NOT NULL
            )";
            if ($conn->query($createParameterTableSQL) === TRUE) {
                Script::consoleLog( "Parameter table created successfully");
            } else {
                Script::consoleLog( "Error creating parameter table: " . $conn->error);
            }
        }
        private static function test($conn){
            Script::consoleLog('test');
        }
        public static function initialClassDiagramDatabase(){
            $conn = Database::connectToDB();
            Database::createDatabaseIfNotExist($conn,'ClassDiagram');
            Database::selectDB($conn,'ClassDiagram');
            self::createDiagramTable($conn);
            self::createClassTable($conn);
            self::createMethodTable($conn);
            self::createParameterTable($conn);
            $conn->close();
        }
    }
?>