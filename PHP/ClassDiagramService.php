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
                diagramID INT(6) UNSIGNED NOT NULL,
                className VARCHAR(30) NOT NULL,
                packagePath VARCHAR(255),
                FOREIGN KEY (diagramID) REFERENCES diagram(diagramID) ON DELETE CASCADE
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating class table: ".$conn->error."<br>";
            } 
        }
        private static function createMethodTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS method(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                diagramID INT(6) UNSIGNED NOT NULL,
                className VARCHAR(30) NOT NULL,
                methodID VARCHAR(16) NOT NULL, 
                methodName VARCHAR(50) NOT NULL,
                returnType VARCHAR(30),
                visibility VARCHAR(8)NOT NULL,
                typeModifier VARCHAR(3),
                isStatic INT(1) NOT NULL,
                FOREIGN KEY (diagramID) REFERENCES diagram(diagramID) ON DELETE CASCADE
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating method table: ".$conn->error."<br>";
            } 
        }
        private static function createParameterTable($conn){
            $sql = "CREATE TABLE IF NOT EXISTS parameter(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                diagramID INT(6) UNSIGNED NOT NULL,
                methodID VARCHAR(16) NOT NULL,
                parameterID VARCHAR(16) NOT NULL, 
                parameterName VARCHAR(30) NOT NULL,
                parameterType VARCHAR(30) NOT NULL,
                typeModifier VARCHAR(3),
                FOREIGN KEY (diagramID) REFERENCES diagram(diagramID) ON DELETE CASCADE
            )";
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating parameter table: ".$conn->error."<br>";
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
        public static function insertToClassTable($conn, $diagramID, $className, $packagePath){
            $sql = $conn->prepare("INSERT INTO class(diagramID,className,packagePath) VALUES(?,?,?)");
            $sql->bind_param("iss",$diagramID,$className,$packagePath);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to class table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToMethodTable($conn, $diagramID,$className, 
        $methodID, $methodName, $returnType,$visibility,$typeModifier,$isStatic){
            $sql = $conn->prepare("INSERT INTO method(diagramID,className,methodID, 
            methodName, returnType, visibility, typeModifier,isStatic) 
            VALUES(?,?,?,?,?,?,?,?)");
            $sql->bind_param("issssssi",$diagramID,$className,$methodID, $methodName,
             $returnType, $visibility,$typeModifier,$isStatic);
            if($sql->execute()===FALSE){
                    echo "Error at inserting to method table: ".$sql->error."<br>";
            }
            $sql->close();
        }
        public static function insertToParameterTable($conn, $diagramID, $methodID, $parameterID, $parameterName, $parameterType, $typeModifier){
            $sql = $conn->prepare("INSERT INTO parameter(diagramID,methodID, parameterID, parameterName, parameterType, typeModifier) 
            VALUES(?,?,?,?,?,?)");
            $sql->bind_param("isssss",$diagramID,$methodID, $parameterID, $parameterName, $parameterType, $typeModifier);
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
        public static function selectAllFromDiagram(){
            $conn = Database::connectToDBUsingPDO('classdiagram');
            $sql = $conn->prepare("SELECT * FROM diagram");
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectClassFromNodeName($diagramID, $nodeName){
            $conn = Database::connectToDBUsingPDO('classDiagram');
            $sql = $conn->prepare("SELECT * FROM class WHERE diagramID = :diagramID AND className = :nodeName LIMIT 1");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':nodeName',$nodeName);
            $sql->execute();
            $result = $sql->fetch();
            return $result;
        }
        public static function selectMethodByMethodName($diagramID, $className, $methodName){
            $conn = Database::connectToDBUsingPDO('classDiagram');
            $sql = $conn->prepare("SELECT * FROM method WHERE diagramID = :diagramID AND 
            className = :className AND 
            methodName = :methodName LIMIT 1");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':className',$className);
            $sql->bindParam(':methodName',$methodName);
            $sql->execute();
            $result = $sql->fetch();
            return $result;
        }
        public static function selectParameterByMethodID($diagramID, $methodID){
            $conn = Database::connectToDBUsingPDO('classDiagram');
            $sql = $conn->prepare("SELECT * FROM parameter WHERE diagramID = :diagramID AND
            methodID = :methodID");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':methodID', $methodID);
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function selectAllMethodFromClassName($diagramID,$className){
            $conn = Database::connectToDBUsingPDO('classDiagram');
            $sql = $conn->prepare("SELECT * FROM method WHERE diagramID = :diagramID AND
            className = :className");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':className', $className);
            $sql->execute();
            $result = $sql->fetchAll();
            return $result;
        }
        public static function deleteFromDiagram($diagramName){
            $conn = Database::connectToDBUsingPDO("classdiagram");
            $sql = $conn->prepare("DELETE FROM diagram WHERE diagramName = :diagramName");
            $sql->bindParam(":diagramName",$diagramName);
            if($sql->execute() === FALSE){
                return false;
            }
            return true;
        }
    }
?>