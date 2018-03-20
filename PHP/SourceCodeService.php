<?php
    require_once "Database.php";
    class SourceCodeService
    {
        private static function createSourceCodeTable($conn){
            $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
                fileID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                fileType VARCHAR(6) NOT NULL,
                language VARCHAR(6) NOT NULL,
                location VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($createFileTableSQL) === FALSE) {
                echo "Error creating source code table: " . $conn->error;
            } 
        }
        public static function initialSourceCodeDatabase(){
            $conn = Database::connectToDB();
            Database::createDatabaseIfNotExist($conn,'SourceCode');
            Database::selectDB($conn,'SourceCode');
            self::createSourceCodeTable($conn);
            $conn->close();
        }
        public static function insertFile($name, $fileType, $language, $location){
            $conn = Database::connectToDB();
            Database::selectDB($conn,'SourceCode');
            $sql = $conn->prepare("INSERT INTO fileTable(name, fileType, language, location) 
            VALUES(?,?,?,?)");
            $sql->bind_param("ssss",$name, $fileType, $language, $location);
            if($sql->execute()===FALSE){
                echo "Error at inserting to source code table: ".$sql->error."<br>";
            }
            $sql->close();
            $conn->close();
            echo "a";
            return self::getFileID($name);
        }
        private static function getFileID($name){
            $conn = Database::connectToDBUsingPDO('SourceCode');
            $sql = $conn->prepare("SELECT fileID FROM fileTable WHERE name = :name LIMIT 1");
            $sql->bindParam(":name",$name);
            $sql->execute();
            $result = $sql->fetch();
            return $result[0];
        }
    }
?>
