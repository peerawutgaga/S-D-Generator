<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
if (isset($_POST['functionName'])) {
    if ($_POST['functionName'] == "getCallGraphList") {
        DiagramSelection::getCallGraphList();
    } else if ($_POST['functionName'] == "getClassDiagramList") {
       DiagramSelection::getClassDiagramList();
    }else if ($_POST['functionName'] == "getObjectListByCallGraphId"&&isset($_POST['callGraphId'])) {
        DiagramSelection::getObjectListByCallGraphId($_POST['callGraphId']);
    }
    else if ($_POST['functionName'] == "checkReferenceDiagram"&&isset($_POST['callGraphId'])) {
        DiagramSelection::checkReferenceDiagram($_POST['callGraphId']);
    }
}

class DiagramSelection
{
    public static function getCallGraphList()
    {
        $graphList = CallGraphService::selectAllFromGraph();
        echo json_encode($graphList);
    }

    public static function getClassDiagramList()
    {
        $diagramList = ClassDiagramService::selectAllFromDiagram();
        echo json_encode($diagramList);
    }
    public static function getObjectListByCallGraphId($callGraphId){
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphIdAndIsNotRef($callGraphId);
        echo json_encode($objectList);
    }
    public static function checkReferenceDiagram($callGraphId){
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphIdWhereObjectIsRef($callGraphId);
        if(count($objectList)==0){
            echo false;
        }else{
            echo json_encode($objectList);      
        }
    }
}
?>