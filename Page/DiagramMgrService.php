<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    if(isset($_POST['getList'])){
        if($_POST['getList']=="Sequence"){
           echo DiagramMgrService::getCallGraphList();
        }else if($_POST['getList']=="ClassDiagram"){
            echo DiagramMgrService::getClassDiagramList();
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
    }
?>