<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    class DiagramMgrService{
        public static function getCallGraphList(){
            return CallGraphService::selectAllFromGraph();
        }
        public static function getClassDiagramList(){
            return ClassDiagramService::selectAllFromDiagram();
        }
    }
?>