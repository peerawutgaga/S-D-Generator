<?php
    require_once "Database.php";
    $diagram = realpath($_SERVER["DOCUMENT_ROOT"])."/Diagram/ClassDiagram/";
    include "$Diagram/ClassDiagram.php";
    include "$Diagram/ObjectClass.php";
    include "$Diagram/Method.php";
    include "$Diagram/Parameter.php";
    use ClassDiagram\ClassDiagram;
    use ClassDiagram\ObjectClass;
    use ClassDiagram\Method;
    use ClassDiagram\Parameter;
    class ClassDiagramService{
        private static function createDiagramTable(){
            $conn = Database::connectToDB("classDiagram");
            $sql =  "CREATE TABLE IF NOT EXISTS diagram(
                diagramID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramName VARCHAR(30) NOT NULL,
                fileTarget VARCHAR(100) NOT NULL,
                createDate TIMESTAMP
            )";
             try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create diagram table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        private static function createClassTable(){
            $conn = Database::connectToDB("classDiagram");
            $sql = "CREATE TABLE IF NOT EXISTS class(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                diagramID INT(6) UNSIGNED NOT NULL,
                className VARCHAR(30) NOT NULL,
                classType INT(1) UNSIGNED NOT NULL,
                packagePath VARCHAR(255),
                FOREIGN KEY (diagramID) REFERENCES diagram(diagramID) ON DELETE CASCADE
            )";
             try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create class table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        private static function createMethodTable(){
            $conn = Database::connectToDB("classDiagram");
            $sql = "CREATE TABLE IF NOT EXISTS method(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                diagramID INT(6) UNSIGNED NOT NULL,
                className VARCHAR(30) NOT NULL,
                methodID VARCHAR(16) NOT NULL, 
                methodName VARCHAR(50) NOT NULL,
                returnType VARCHAR(30),
                returnTypeModifier VARCHAR(3),
                visibility VARCHAR(8)NOT NULL,
                isStatic INT(1) NOT NULL,
                isAbstract INT(1) NOT NULL,
                FOREIGN KEY (diagramID) REFERENCES diagram(diagramID) ON DELETE CASCADE
            )";
             try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create method table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        private static function createParameterTable(){
            $conn = Database::connectToDB("classDiagram");
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
            try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create graph table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        public static function initialClassDiagramDatabase(){
            Database::createDatabaseIfNotExist('ClassDiagram');
            self::createDiagramTable();
            self::createClassTable();
            self::createMethodTable();
            self::createParameterTable();
        }
        public static function insertToDiagramTable(ClassDiagram $classDiagram){
            $conn = Database::connectToDB("classDiagram");
            $sql = "INSERT INTO diagram(diagramName, fileTarget) VALUES(:diagramName, :fileTarget)";
            $sql->bindParam(":diagramName",$classDiagram->getDiagramName());
            $sql->bindParam(":fileTarget",$classDiagram->getFileTarget());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to diagram table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function insertToClassTable($diagramID, ObjectClass $objectClass){
            $conn = Database::connectToDB("classDiagram");
            $sql = "INSERT INTO class(diagramID,className, classType,packagePath) 
                VALUES(:diagramID,:className, :classType,:packagePath)";
            $sql->bindParam(":diagramID",$diagramID);
            $sql->bindParam(":className",$objectClass->getClassName());
            $sql->bindParam(":classType",$objectClass->getClassType());
            $sql->bindParam(":packagePath",$objectClass->getPackagePath());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to class table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function insertToMethodTable($diagramID, $className, Method $method){
            $conn = Database::connectToDB("classDiagram");
            $sql = $conn->prepare("INSERT INTO method(diagramID,className,methodID, 
            methodName, returnType, returnTypeModifier,visibility, isStatic, isAbStract) 
            VALUES(:diagramID,:className,:methodID, 
            :methodName, :returnType, :returnTypeModifier, :visibility,:isStatic, :isAbStract)");
            $sql->bindParam(":diagramID",$diagramID);
            $sql->bindParam(":className",$className);
            $sql->bindParam(":methodID",$method->getMethodID());
            $sql->bindParam(":methodName",$method->getMethodName());
            $sql->bindParam(":returnType",$method->getReturnType());
            $sql->bindParam(":returnTypeModifier",$method->getReturnTypeModifier());
            $sql->bindParam(":visibility",$method->getVisibility());
            $sql->bindParam(":isStatic",$method->getIsStatic());
            $sql->bindParam(":isAbstract",$method->getIsAbstract());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to method table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function insertToParameterTable($diagramID, $methodID,Parameter $parameter){
            $conn = Database::connectToDB("classDiagram");
            $sql = $conn->prepare("INSERT INTO parameter(diagramID,methodID, parameterID, parameterName, parameterType, typeModifier) 
            VALUES(:diagramID,:methodID, :parameterID, :parameterName, :parameterType, :typeModifier)");
            $sql->bindParam(":diagramID",$diagramID);
            $sql->bindParam(":methodID",$methodID);
            $sql->bindParam(":parameterID",$parameter->getParameterID());
            $sql->bindParam(":parameterName",$parameter->getParameterName());
            $sql->bindParam(":parameterType",$parameter->getParameterType());
            $sql->bindParam(":typeModifier",$parameter->getTypeModifier());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to parameter table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectFromDiagramByDiagramID($diagram){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM diagram WHERE diagramID = :diagramID LIMIT 1");          
            $sql->bindParam(':diagramID',$diagramID);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from diagram table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectFromDiagramByDiagramName($diagramName){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM diagram WHERE diagramName = :diagramName LIMIT 1");          
            $sql->bindParam(':diagramName',$diagramName);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from diagram table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectAllFromDiagram(){
            $conn = Database::connectToDB("classDiagram");
            $sql = $conn->prepare("SELECT * FROM diagram");
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from diagram table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectClassFromNodeName($diagramID, $nodeName){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM class WHERE diagramID = :diagramID AND className = :nodeName LIMIT 1");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':nodeName',$nodeName);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from class table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectMethodByMethodName($diagramID, $className, $methodName){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM method WHERE diagramID = :diagramID AND 
            className = :className AND 
            methodName = :methodName LIMIT 1");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':className',$className);
            $sql->bindParam(':methodName',$methodName);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from method table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectParameterByMethodID($diagramID, $methodID){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM parameter WHERE diagramID = :diagramID AND
            methodID = :methodID");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':methodID', $methodID);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from parameter table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectMethodByClassName($diagramID,$className){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("SELECT * FROM method WHERE diagramID = :diagramID AND
            className = :className");
            $sql->bindParam(':diagramID',$diagramID);
            $sql->bindParam(':className', $className);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from method table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function deleteFromDiagram($diagramName){
            $conn = Database::connectToDB("classdiagram");
            $sql = $conn->prepare("DELETE FROM diagram WHERE diagramName = :diagramName");
            $sql->bindParam(":diagramName",$diagramName);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at delete diagram " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
        public static function renameDiagram($oldName,$newName, $path){
            $conn = Database::connectToDB('classDiagram');
            $sql = $conn->prepare("UPDATE diagram SET diagramName = :newName, fileTarget = :path WHERE diagramName = :oldName");
            $sql->bindParam(":newName",$newName);
            $sql->bindParam(":oldName",$oldName);
            $sql->bindParam(":path",$path);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at rename diagram " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
    }
?>