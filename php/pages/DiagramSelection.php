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
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphId($callGraphId);
        echo json_encode($objectList);
    }
}
?>