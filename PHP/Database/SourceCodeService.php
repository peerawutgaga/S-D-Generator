<?php
    require_once "Database.php";
    class SourceCodeService
    {
        private static function createSourceCodeTable(){
            $conn = Database::connectToDB("sourceCode");
            $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
                name VARCHAR(100) PRIMARY KEY,
                fileType VARCHAR(6) NOT NULL,
                language VARCHAR(6) NOT NULL,
                location VARCHAR(255) NOT NULL,
                createDate TIMESTAMP
            )";
             try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create source code table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        public static function initialSourceCodeDatabase(){
            Database::createDatabaseIfNotExist('SourceCode');
            self::createSourceCodeTable();
        }
        public static function insertFile($name, $fileType, $language, $location){
            $conn = Database::connectToDB("sourceCode");
            $sql = "INSERT INTO fileTable(name, fileType, language, location) 
            VALUES(:name, :fileType, :language, :location)";
            $sql->bindParam(":name",$name);
            $sql->bindParam(":fileType",$fileType);
            $sql->bindParam(":language",$language);
            $sql->bindParam(":location",$location);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at insert to file table " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
        public static function renameFile($oldName,$newName,$path){
            $conn = Database::connectToDB('sourcecode');
            $sql = $conn->prepare("UPDATE fileTable SET name = :newName, location = :path WHERE name = :oldName");
            $sql->bindParam(":newName",$newName);
            $sql->bindParam(":oldName",$oldName);
            $sql->bindParam(":path",$path);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at rename file " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
        public static function selectAllFromFileTable(){
            $conn = Database::connectToDB('sourcecode');
            $sql = $conn->prepare("SELECT * FROM fileTable");
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from file table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectFromFileTableByFileName($fileName){
            $conn = Database::connectToDB('sourcecode');
            $sql = $conn->prepare("SELECT * FROM fileTable WHERE name = :fileName LIMIT 1");
            $sql->bindParam(":fileName",$fileName);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from file table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function deleteFile($filename){
            $conn = Database::connectToDB("sourcecode");
            $sql = $conn->prepare("DELETE FROM fileTable WHERE name = :filename");
            $sql->bindParam(":filename",$filename);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at delete file " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
    }
?>
