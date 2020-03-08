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
    } else if($_POST['functionName'] == "renameCallGraph"&& isset($_POST['callGraphId'])&& isset($_POST['newFilename'])){
        DiagramManagerPage::renameCallGraph($_POST['callGraphId'],$_POST['newFilename']);
    }else if($_POST['functionName'] == "renameClassDiagram"&& isset($_POST['diagramId'])&& isset($_POST['newFilename'])){
        DiagramManagerPage::renameClassDiagram($_POST['diagramId'], $_POST['newFilename']);
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
        $success = CallGraphService::deleteFromGraphByCallGraphId($callGraphId);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
    }

    public static function deleteClassDiagram($diagramId)
    {
        $success = ClassDiagramService::deleteFromDiagramByDiagramId($diagramId);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
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
    public static function renameCallGraph($callGraphId,$newFilename){
        $success = CallGraphService::updateGraphSetCallGraphNameByCallGraphId($callGraphId, $newFilename);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
    }
    public static function renameClassDiagram($diagramId,$newFilename){
        $success = ClassDiagramService::updateDiagramSetDiagramNameByDiagramId($diagramId, $newFilename);
        if($success){
            echo "success";
        }else{
            echo "failed";
        }
    }
}
?>