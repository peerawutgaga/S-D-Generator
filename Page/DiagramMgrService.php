<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
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