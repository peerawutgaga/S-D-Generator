<?php
    require_once "Database.php";
    $diagram = realpath($_SERVER["DOCUMENT_ROOT"])."/Diagram/SequenceDiagram/";
    include "$Diagram/CallGraph.php";
    include "$Diagram/ObjectNode.php";
    include "$Diagram/Message.php";
    include "$Diagram/Argument.php";
    use SequenceDiagram\CallGraph;
    use SequenceDiagram\ObjectNode;
    use SequenceDiagram\Message;
    use SequenceDiagram\Argument;
    class CallGraphService{
        private static function createGraphTable(){
            $conn = Database::connectToDB("callgraph");
            $sql =  "CREATE TABLE IF NOT EXISTS graph(
                graphID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                graphName VARCHAR(100) NOT NULL,
                fileTarget VARCHAR(255) NOT NULL,
                createDate TIMESTAMP
            )";
            try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create graph table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        private static function createNodeTable(){
            $conn = Database::connectToDB("callgraph");
            $sql =  "CREATE TABLE IF NOT EXISTS node(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                graphID INT(6) UNSIGNED NOT NULL,
                FOREIGN KEY (graphID) REFERENCES graph(graphID) ON DELETE CASCADE,
                nodeID VARCHAR(16) NOT NULL, 
                nodeName VARCHAR(50) NOT NULL
            )";
            try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create node table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }
        private static function createMessageTable(){
            $conn = Database::connectToDB("callgraph");
            $sql =  "CREATE TABLE IF NOT EXISTS message(
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                graphID INT(6) UNSIGNED NOT NULL,
                FOREIGN KEY (graphID) REFERENCES graph(graphID) ON DELETE CASCADE,
                messageID VARCHAR(16)NOT NULL, 
                messageName VARCHAR(50) NOT NULL,
                sentNodeID VARCHAR(16) NOT NULL,
                receivedNodeID VARCHAR(16) NOT NULL
            )";
            try{
                $conn->exec($sql);
            }catch(PDOException $e){
                die("Create message table failed " . $e->getMessage());
            }finally{
                $conn = null;
            }
        } 
        public static function initialCallGraphDatabase(){
            Database::createDatabaseIfNotExist('CallGraph');
            self::createGraphTable();
            self::createNodeTable();
            self::createMessageTable();
        }
        public static function insertToGraphTable(CallGraph $callGraph){
            $conn = Database::connectToDB("CallGraph");
            $sql = $conn->prepare("INSERT INTO graph(graphName, fileTarget) VALUES(:graphName,:fileTarget)");
            $sql->bindParam(":graphName",$callGraph->getGraphName());
            $sql->bindParam(":fileTarget",$callGraph->getFileTarget());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to graph table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function insertToNodeTable($graphID, ObjectNode $node){
            $conn = Database::connectToDB("CallGraph");
            $sql = $conn->prepare("INSERT INTO node(graphID, nodeID, nodeName) VALUES(:graphID,:nodeID,:nodeName)");
            $sql->bindParam(":graphID",$graphID);
            $sql->bindParam(":nodeID",$node->getNodeID());
            $sql->bindParam(":nodeName",$node->getNodeName());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to node table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function insertToMessageTable($graphID, Message $message){
            $conn = Database::connectToDB("CallGraph");
            $sql = $conn->prepare("INSERT INTO message(graphID, messageID, messageName, sentNodeID, receivedNodeID) 
                VALUES(:graphID, :messageID, :messageName, :sentNodeID, :receivedNodeID)");
            $sql->bindParam(":graphID",$graphID);
            $sql->bindParam(":messageID",$message->getMessageID());
            $sql->bindParam(":messageName",$message->getMessageName());
            $sql->bindParam(":sentNodeID",$message->getSentNodeID());
            $sql->bindParam(":receivedNodeID",$message->getReceivedNodeID());
            try{
                $sql->execute();
            }catch(PDOException $e){
                echo "Error at insert to message table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectFromGraphByGraphID($graphID){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM graph WHERE graphID = :graphID LIMIT 1");          
            $sql->bindParam(':graphID',$graphID);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from graph table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectFromGraphByGraphName($graphName){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM graph WHERE graphName = :graphName LIMIT 1");          
            $sql->bindParam(':graphName',$graphName);
            try{
                $sql->execute();
                $result = $sql->fetch();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from graph table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectAllFromGraph(){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM graph");
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from graph table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectAllFromNode($graphID){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM node WHERE graphID = :graphID");
            $sql->bindParam(':graphID',$graphID);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from node table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectMessageBySentNodeID($graphID, $sentNodeID){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM message WHERE graphID = :graphID AND sentNodeID = :sentNodeID");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':sentNodeID',$sentNodeID);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from message table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectMessageByReceivedNodeID($graphID, $receivedNodeID){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM message WHERE graphID = :graphID AND receivedNodeID = :receivedNodeID");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':receivedNodeID',$receivedNodeID);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from message table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function selectNodeByNodeID($graphID, $nodeID){
            $conn = Database::connectToDB('callGraph');
            $sql = $conn->prepare("SELECT * FROM node WHERE graphID = :graphID AND nodeID = :nodeID LIMIT 1");
            $sql->bindParam(':graphID',$graphID);
            $sql->bindParam(':nodeID',$nodeID);
            try{
                $sql->execute();
                $result = $sql->fetchAll();
                return $result;
            }catch(PDOException $e){
                echo "Error at selecting from node table " . $e->getMessage();
            }finally{
                $conn = null;
            }
        }
        public static function deleteFromGraph($graphName){
            $conn = Database::connectToDB("callgraph");
            $sql = $conn->prepare("DELETE FROM graph WHERE graphName = :graphName");
            $sql->bindParam(":graphName",$graphName);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at delete graph " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
        public static function renameGraph($oldName,$newName, $path){
            $conn = Database::connectToDB('callgraph');
            $sql = $conn->prepare("UPDATE graph SET graphName = :newName, fileTarget = :path WHERE graphName = :oldName");
            $sql->bindParam(":newName",$newName);
            $sql->bindParam(":oldName",$oldName);
            $sql->bindParam(":path",$path);
            try{
                $sql->execute();
                return true;
            }catch(PDOException $e){
                echo "Error at rename graph " . $e->getMessage();
                return false;
            }finally{
                $conn = null;
            }
        }
    }

?>