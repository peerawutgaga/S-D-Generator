<?php
require_once "Database.php";

class SourceCodeService
{
    //TODO Interface change aware
    public static function insertFile($fileName, $fileType, $language, $location)
    {
        $conn = Database::connectToDB("sourceCode");
        $sql = $conn->prepare("INSERT INTO fileTable(fileName, fileType, language, location) 
            VALUES(:fileName, :fileType, :language, :location)");
        $sql->bindParam(":fileName", $fileName);
        $sql->bindParam(":fileType", $fileType);
        $sql->bindParam(":language", $language);
        $sql->bindParam(":location", $location);
        try {
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            // echo "Error at insert to file table " . $e->getMessage();
            return false;
        } finally{
            $conn = null;
        }
    }

    public static function renameFile($oldName, $newName, $path)
    {
        $conn = Database::connectToDB('sourcecode');
        $sql = $conn->prepare("UPDATE fileTable SET fileName = :newName, location = :path WHERE fileName = :oldName");
        $sql->bindParam(":newName", $newName);
        $sql->bindParam(":oldName", $oldName);
        $sql->bindParam(":path", $path);
        try {
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error at rename file " . $e->getMessage() . "<br>";
            return false;
        } finally{
            $conn = null;
        }
    }

    public static function selectAllFromFileTable()
    {
        $conn = Database::connectToDB('sourcecode');
        $sql = $conn->prepare("SELECT * FROM fileTable");
        try {
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo "Error at selecting from file table " . $e->getMessage() . "<br>";
        } finally{
            $conn = null;
        }
    }

    public static function selectFromFileTableByFileName($fileName)
    {
        $conn = Database::connectToDB('sourcecode');
        $sql = $conn->prepare("SELECT * FROM fileTable WHERE fileName = :fileName LIMIT 1");
        $sql->bindParam(":fileName", $fileName);
        try {
            $sql->execute();
            $result = $sql->fetch();
            return $result;
        } catch (PDOException $e) {
            echo "Error at selecting from file table " . $e->getMessage() . "<br>";
        } finally{
            $conn = null;
        }
    }

    public static function deleteFile($filename)
    {
        $conn = Database::connectToDB("sourcecode");
        $sql = $conn->prepare("DELETE FROM fileTable WHERE fileName = :filename");
        $sql->bindParam(":filename", $filename);
        try {
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error at delete file " . $e->getMessage() . "<br>";
            return false;
        } finally{
            $conn = null;
        }
    }
}
?>
