<?php
    require_once "Database.php";
    class ClassDiagramService{
        private static function createDiagramTable($conn){
            $sql =  "CREATE TABLE IF NOT EXISTS diagram(
                diagramID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramName VARCHAR(30) NOT NULL,
                fileTarget VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createClassTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS class(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramID INT(6) NOT NULL,
                classID VARCHAR(16) NOT NULL,
                className VARCHAR(30) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createMethodTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS method(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                diagramID INT(6) NOT NULL,
                classID VARCHAR(16) NOT NULL,
                methodID VARCHAR(16) NOT NULL, 
                methodName VARCHAR(50) NOT NULL,
                returnType VARCHAR(30) NOT NULL
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating graph table: ".$conn->error."<br>";
            } 
        }
        private static function createParameterTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS parameter(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                diagramID INT(6) NOT NULL,
                methodID VARCHAR(16) NOT NULL,
                parameterID VARCHAR(16) NOT NULL, 
                parameterName VARCHAR(30) NOT NULL,
                parameterType VARCHAR(30) NOT NULL
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

        public static function insertToDiagramTable($conn, $diagramName, $fileTarget){
            $sql = $conn->prepare("INSERT INTO diagram(diagramName, fileTarget) VALUES(?,?)");
            $sql->bind_param("ss",$diagramName,$fileTarget);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to diagram table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToClassTable($conn, $diagramID, $classID, $className){
            $sql = $conn->prepare("INSERT INTO class(diagramID, classID, className) VALUES(?,?,?)");
            $sql->bind_param("iss",$diagramID,$classID, $className);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to class table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToMethodTable($conn, $diagramID, $classID, $methodID, $methodName, $returnType){
            $sql = $conn->prepare("INSERT INTO method(diagramID, classID, methodID, methodName, returnType) VALUES(?,?,?,?,?)");
            $sql->bind_param("issss",$diagramID,$classID, $methodID, $methodName, $returnType);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to method table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToParameterTable($conn, $diagramID, $methodID, $parameterID, $parameterName, $parameterType){
            $sql = $conn->prepare("INSERT INTO parameter(diagramID,methodID, parameterID, parameterName, parameterType) VALUES(?,?,?,?,?)");
            $sql->bind_param("issss",$diagramID,$methodID, $parameterID, $parameterName, $parameterType);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to parameter table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function selectFromDiagramTable($value,$field,$keyword){
            $conn = Database::connectToDBUsingPDO('classdiagram');
            if($field == 'diagramID'){
                $sql = $conn->prepare("SELECT * FROM diagram WHERE diagramID = :keyword LIMIT 1");
            }else if($field == 'diagramName'){
                $sql = $conn->prepare("SELECT * FROM diagram WHERE diagramName = :keyword LIMIT 1");
            }
            $sql->bindParam(':keyword',$keyword);
            $sql->execute();
            $result = $sql->fetch();
            return $result[$value];
        }
    }
?>