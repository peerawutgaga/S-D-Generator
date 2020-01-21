<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/utilities/LocalFileManager.php";

if (isset($_POST['functionName'])) {
    if ($_POST['functionName'] == "getCallGraphList") {
        DiagramManagerPage::getCallGraphList();
    } else if ($_POST['functionName'] == "getClassDiagramList") {
        DiagramManagerPage::getClassDiagramList();
    } else if ($_POST['functionName'] == "deleteCallGraph" && isset($_POST['callGraphId'])) {
        DiagramManagerPage::deleteCallGraph($_POST['callGraphId']);
    } else if ($_POST['functionName'] == "deleteClassDiagram" && isset($_POST['diagramId'])) {
        DiagramManagerPage::deleteClassDiagram($_POST['diagramId']);
    } else if ($_POST['functionName'] == "getReferenceObjectList" && isset($_POST['callGraphId'])) {
        DiagramManagerPage::getReferenceObjectList($_POST['callGraphId']);
    } else if ($_POST['functionName'] == "connectReferenceDiagram" && isset($_POST['sourceCallGraphId']) && isset($_POST['destinationCallGraphId']) && isset($_POST['referenceObjectId'])) {
        DiagramManagerPage::connectReferenceDiagram($_POST['referenceObjectId'],$_POST['sourceCallGraphId'], $_POST['destinationCallGraphId'] );
    }
}

class DiagramManagerPage
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

    public static function deleteCallGraph($callGraphId)
    {
        // TODO delete call graph
    }

    public static function deleteClassDiagram($diagramId)
    {
        // TODO delete class diagram
    }

    public static function getReferenceObjectList($callGraphId)
    {
        $refObjList = CallGraphService::selectFromObjectNodeByCallGraphIdAndIsRef($callGraphId);
        if (count($refObjList) == 0) {
            echo "NONE";
        } else {
            echo json_encode($refObjList);
        }
    }

    public static function connectReferenceDiagram($referenceObjectId,$sourceCallGraphId, $destinationCallGraphId )
    {
        $connection = CallGraphService::selectFromReferenceDiagramByObjectId($referenceObjectId);
        if(count($connection)==0){
            CallGraphService::insertIntoReferenceDiagram($referenceObjectId,$sourceCallGraphId, $destinationCallGraphId);
            echo "INSERT";
        }else{
            CallGraphService::updateRefDiagramSetDestinationGraphIdByObjectId($destinationCallGraphId, $referenceObjectId);
            echo "UPDATE";
        }
    }
}
?>