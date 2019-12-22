<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/Database.php";
require_once "$root/php/utilities/Logger.php";
class ClassDiagramService
{
    public static function insertIntoDiagram($diagramName, $filePath)
    {
        $conn = Database::getConnection();
        $diagramId = - 1;
        $sql = $conn->prepare("INSERT INTO `classdiagram.diagram`(`diagramName`, `filePath`) VALUES(:diagramName, :filePath)");
        $sql->bindParam(":diagramName", $diagramName);
        $sql->bindParam(":filePath", $filePath);
        try {
            $sql->execute();
            $diagramId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $diagramId;
    }

    public static function insertIntoPackage($diagramId, $packageName, $namespace)
    {
        $conn = Database::getConnection();
        $packageId = - 1;
        $sql = $conn->prepare("INSERT INTO `classdiagram.package`(`diagramId`,`packageName`, `namespace`) VALUES(:diagramId, :packageName,:namespace)");
        $sql->bindParam(":diagramId", $diagramId);
        $sql->bindParam(":packageName", $packageName);
        $sql->bindParam(":namespace", $namespace);
        try {
            $sql->execute();
            $packageId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $packageId;
    }

    public static function insertIntoClass($packageId, $className, $instanceType)
    {
        $conn = Database::getConnection();
        $classId = - 1;
        $instanceType = strtoupper($instanceType);
        $sql = $conn->prepare("INSERT INTO `classdiagram.class`(`packageId`,`className`, `instanceType`) 
                VALUES(:packageId,:className, :instanceType)");
        $sql->bindParam(":packageId", $packageId);
        $sql->bindParam(":className", $className);
        $sql->bindParam(":instanceType", $instanceType);
        try {
            $sql->execute();
            $classId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $classId;
    }

    public static function insertIntoMethod($classId, $methodName, $visibility, $returnType, $typeModifier, $instanceType, $isConstructor)
    {
        $conn = Database::getConnection();
        $methodId = - 1;
        $visibility = strtolower($visibility);
        $instanceType = strtoupper($instanceType);
        $sql = $conn->prepare("INSERT INTO `classdiagram.method`(`classId`, `methodName`, `visibility`, `returnType`, `typeModifier`,`instanceType`, `isConstructor`) 
            VALUES(:classId,:methodName,:visibility, :returnType, :typeModifier,:instanceType, :isConstructor)");
        $sql->bindParam(":classId", $classId);
        $sql->bindParam(":methodName", $methodName);
        $sql->bindParam(":visibility", $visibility);
        $sql->bindParam(":returnType", $returnType);
        $sql->bindParam(":typeModifier", $typeModifier);
        $sql->bindParam(":instanceType", $instanceType);
        $sql->bindParam(":isConstructor", $isConstructor);
        try {
            $sql->execute();
            $methodId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $methodId;
    }

    public static function insertIntoParam($methodId, $paramName, $dataType, $typeModifier,$seqIdx)
    {
        $conn = Database::getConnection();
        $paramId = - 1;
        $sql = $conn->prepare("INSERT INTO `classdiagram.param`(`methodId`,`paramName`, `dataType`, `typeModifier`, `seqIdx`) 
            VALUES(:methodId,:paramName, :dataType, :typeModifier, :seqIdx)");
        $sql->bindParam(":methodId", $methodId);
        $sql->bindParam(":paramName", $paramName);
        $sql->bindParam(":dataType", $dataType);
        $sql->bindParam(":typeModifier", $typeModifier);
        $sql->bindParam(":seqIdx", $seqIdx);
        try {
            $sql->execute();
            $paramId = $conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $paramId;
    }
    public static function insertIntoInheritance($superClassId,$childClassId){
        $conn = Database::getConnection();
        $sql = $conn->prepare("INSERT INTO `classdiagram.inheritance`(`superClassId`,`childClassId`)
            VALUES(:superClassId,:childClassId)");
        $sql->bindParam(":superClassId", $superClassId);
        $sql->bindParam(":childClassId", $childClassId);
        try {
            $sql->execute();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
    }
    public static function selectFromDiagramByDiagramId($diagramId)
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `classdiagram.diagram` WHERE `diagramId` = :diagramId");
        $sql->bindParam(':diagramId', $diagramId);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }

    public static function selectAllFromDiagram()
    {
        $conn = Database::getConnection();
        $sql = $conn->prepare("SELECT * FROM `classdiagram.diagram`");
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }

    public static function selectClassByDiagramIdAndObjectBase($diagramId, $baseIdentifier)
    {
        $conn = Database::getConnection();
        $statement = "SELECT p.namespace, c.classId, c.className, c.InstanceType FROM `classdiagram.diagram` d 
        INNER JOIN `classdiagram.package` p on d.diagramId = p.diagramId
        INNER JOIN `classdiagram.class` c on p.packageId = c.packageId
        WHERE d.diagramId = :diagramId AND c.className = :baseIdentifier";
        $sql = $conn->prepare($statement);
        $sql->bindParam(':diagramId', $diagramId);
        $sql->bindParam(':baseIdentifier', $baseIdentifier);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
    public static function selectMethodByClassIdAndMessageName($classId,$messageName){
        $conn = Database::getConnection();
        $statement = "SELECT * FROM `classdiagram.method` 
        WHERE classId = :classId AND methodName = :messageName";
        $sql = $conn->prepare($statement);
        $sql->bindParam(':classId', $classId);
        $sql->bindParam(':messageName', $messageName);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
    public static function selectParamByMethodIdAndArguName($methodId,$arguName){
        $conn = Database::getConnection();
        $statement = "SELECT * FROM `classdiagram.param`
        WHERE methodId = :methodId AND paramName = :arguName";
        $sql = $conn->prepare($statement);
        $sql->bindParam(':methodId', $methodId);
        $sql->bindParam(':arguName', $arguName);
        try {
            $sql->execute();
            $result = $sql->fetchAll();
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
    public static function deleteFromDiagramByDiagramId($diagramId){
        $conn = Database::getConnection();
        $result = false;
        $sql = $conn->prepare("DELETE FROM `classdiagram.diagram` where diagramId = :diagramId");
        $sql->bindParam(':diagramId', $diagramId);
        try {
            $sql->execute();
            $result = true;
        } catch (PDOException $e) {
            Logger::logDatabaseError("ClassDiagramService",$e->getMessage());
        } finally{
             unset($conn);
        }
        return $result;
    }
}
?>