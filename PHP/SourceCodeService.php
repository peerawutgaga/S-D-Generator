<?php
    require_once "Database.php";
    class SourceCodeService
    {
        private static function createSourceCodeTable($conn){
            $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
                fileID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                fileName VARCHAR(30) NOT NULL,
                fileType VARCHAR(4) NOT NULL,
                fileLocation VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($createFileTableSQL) === TRUE) {
                Script::consoleLog("Source code table created successfully");
            } else {
                Script::consoleLog("Error creating source code table: " . $conn->error);
            }
        }
        public static function initialSourceCodeDatabase(){
            $conn = Database::connectToDB();
            Database::createDatabaseIfNotExist($conn,'SourceCode');
            Database::selectDB($conn,'SourceCode');
            createSourceCodeTable($conn);
            $conn->close();
        }
    }
?>
