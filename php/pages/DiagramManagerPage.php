<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root."/php/database/CallGraphService.php";
require_once $root."/php/database/ClassDiagramService.php";
require_once $root."/php/utilities/LocalFileManager.php";

if (isset($_POST['functionName'])) {
    if ($_POST['functionName'] == "getCallGraphList") {
        DiagramManagerPage::getCallGraphList();
    } else if ($_POST['functionName'] == "getClassDiagramList") {
        DiagramManagerPage::getClassDiagramList();
    } else if($_POST['functionName'] == "deleteCallGraph"&& isset($_POST['callGraphId'])){
        DiagramManagerPage::deleteCallGraph($_POST['callGraphId']);
    }else if($_POST['functionName'] == "deleteClassDiagram"&& isset($_POST['diagramId'])){
        DiagramManagerPage::deleteClassDiagram($_POST['diagramId']);
    }
}
class DiagramManagerPage
{
    public static function getCallGraphList(){
        $graphList = CallGraphService::selectAllFromGraph();
        echo json_encode($graphList);
    }
    public static function getClassDiagramList(){
        $diagramList = ClassDiagramService::selectAllFromDiagram();
        echo json_encode($diagramList);
    }
    public static function deleteCallGraph($callGraphId){
        //TODO delete call graph
    }
    public static function deleteClassDiagram($diagramId){
        //TODO delete class diagram
    }
}
?>