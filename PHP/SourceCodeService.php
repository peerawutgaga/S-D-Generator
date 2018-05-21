<?php
    require_once "Database.php";
    class SourceCodeService
    {
        private static function createSourceCodeTable($conn){
            $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
                name VARCHAR(30) PRIMARY KEY,
                fileType VARCHAR(6) NOT NULL,
                language VARCHAR(6) NOT NULL,
                location VARCHAR(255) NOT NULL,
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
                $sql->close();
                $conn->close();
                return false;
            }
            $sql->close();
            $conn->close();
            return true;
        }
        public static function renameFile($oldName,$newName,$path){
            $conn = Database::connectToDBUsingPDO('sourcecode');
            $sql = $conn->prepare("UPDATE fileTable SET name = :newName, location = :path WHERE name = :oldName");
            $sql->bindParam(":newName",$newName);
            $sql->bindParam(":oldName",$oldName);
            $sql->bindParam(":path",$path);
            if($sql->execute()==FALSE){
                return false;
            }
            return true;
        }
        public static function selectAllFromFileTable(){
            $conn = Database::connectToDBUsingPDO('sourcecode');
            $sql = $conn->prepare("SELECT * FROM fileTable");
            $sql->execute();
            return $sql->fetchAll();
        }
        public static function selectFromFileTable($fileName){
            $conn = Database::connectToDBUsingPDO('sourcecode');
            $sql = $conn->prepare("SELECT * FROM fileTable WHERE name = :fileName LIMIT 1");
            $sql->bindParam(":fileName",$fileName);
            $sql->execute();
            return $sql->fetch();
        }
        public static function deleteFile($filename){
            $conn = Database::connectToDBUsingPDO("sourcecode");
            $sql = $conn->prepare("DELETE FROM fileTable WHERE name = :filename");
            $sql->bindParam(":filename",$filename);
            if($sql->execute() === FALSE){
                return false;
            }
            return true;
        }
    }
?>
