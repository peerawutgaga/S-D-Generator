<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/Database/CallGraphService.php";
    require_once "$root/PHP/Database/ClassDiagramService.php";
    require_once "$root/PHP/LocalFileManager.php";
    if(isset($_POST['getList'])){
        if($_POST['getList']=="Sequence"){
           echo DiagramMgrService::getCallGraphList();
        }else if($_POST['getList']=="ClassDiagram"){
            echo DiagramMgrService::getClassDiagramList();
        }
    }
    if(isset($_POST['delete'])&&isset($_POST['table'])){
        if($_POST['table'] == "CallGraph"){
            echo DiagramMgrService::deleteCallGraph($_POST['delete']);
        }else if($_POST['table'] == "ClassDiagram"){
            echo DiagramMgrService::deleteClassDiagram($_POST['delete']);
        }
    }
    if(isset($_POST['rename'])&&isset($_POST['table'])){
        if($_POST['table'] == "CallGraph"){
            echo DiagramMgrService::renameCallGraph($_POST['rename'],$_POST['newName']);
        }else if($_POST['table'] == "ClassDiagram"){
            echo DiagramMgrService::renameClassDiagram($_POST['rename'],$_POST['newName']);
        }
    }
    class DiagramMgrService{
        public static function getCallGraphList(){
            $callGraphList = CallGraphService::selectAllFromGraph();
            return json_encode($callGraphList);
        }
        public static function getClassDiagramList(){
            $classDiagramList = ClassDiagramService::selectAllFromDiagram();
            return json_encode($classDiagramList);
        }
        public static function renameCallGraph($oldName, $newName){
            $fullOldName = "../Sequence Diagrams/".$oldName;
            $fullNewName = "../Sequence Diagrams/".$newName;
            if(CallGraphService::selectFromGraphTable("graphName","graphName",$newName) != null){
                echo "Exist";
                return;
            }
            $success = CallGraphService::renameGraph($oldName, $newName, $fullNewName);
            if($success){
                if(rename($fullOldName,$fullNewName)){
                    echo "success";
                    return;
                }
            }
            echo "failed";
        }
        public static function deleteCallGraph($fileName){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = CallGraphService::deleteFromGraph($fileName);
            if($success){
                if(LocalFileManager::delete("$root/Sequence Diagrams/".$fileName)){
                    return "success";
                }
            }
            return "fail";
        }
        public static function renameClassDiagram($oldName, $newName){
            $fullOldName = "../Class Diagrams/".$oldName;
            $fullNewName = "../Class Diagrams/".$newName;
            if(ClassDiagramService::selectFromDiagramTable("diagramName","diagramName",$newName) != null){
                echo "Exist";
                return;
            }
            $success = ClassDiagramService::renameDiagram($oldName, $newName, $fullNewName);
            if($success){
                if(rename($fullOldName,$fullNewName)){
                    echo "success";
                    return;
                }
            }
            echo "failed";
        }
        public static function deleteClassDiagram($fileName){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = ClassDiagramService::deleteFromDiagram($fileName);
            if($success){
                if(LocalFileManager::delete("$root/Class Diagrams/".$fileName)){
                    return "success";
                }
            }
            return "fail";
        }
    }
?>