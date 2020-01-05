<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/utilities/Constant.php";
if (isset($_POST['functionName'])) {
    if ($_POST['functionName'] == "getCallGraphList") {
        DiagramSelection::getCallGraphList();
    } else if ($_POST['functionName'] == "getClassDiagramList") {
        DiagramSelection::getClassDiagramList();
    } else if ($_POST['functionName'] == "getObjectListByCallGraphId" && isset($_POST['callGraphId'])) {
        DiagramSelection::getObjectListByCallGraphId($_POST['callGraphId']);
    } else if ($_POST['functionName'] == "checkReferenceDiagram" && isset($_POST['callGraphId'])) {
        DiagramSelection::checkReferenceDiagram($_POST['callGraphId']);
    } else if ($_POST['functionName'] == "checkClassesRelation" && isset($_POST['callGraphId']) && isset($_POST['objectList'])) {
        DiagramSelection::checkClassesRelation($_POST['callGraphId'], $_POST['objectList']);
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

    public static function getObjectListByCallGraphId($callGraphId)
    {
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphIdAndIsNotRef($callGraphId);
        echo json_encode($objectList);
    }

    public static function checkReferenceDiagram($callGraphId)
    {
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphIdWhereObjectIsRef($callGraphId);
        if (count($objectList) == 0) {
            echo false;
        } else {
            echo json_encode($objectList);
        }
    }
    public static function checkClassesRelation($graphId, $objectListStr){
        $output = self::checkUnrelatedClassSelection($graphId, $objectListStr);
        echo json_encode($output);
    }
    private static function checkUnrelatedClassSelection($graphId, $objectListStr)
    {
        $output = array();
        $objectList = explode(",", $objectListStr);
        if (count($objectList) == 1) {
            array_push($output,array("isSuccess"=>"success"));
            return $output;
        }
        $graphStructure = self::getGraphStructure($graphId);
        if(count($objectList)==count($graphStructure)){
            array_push($output,array("isSuccess"=>"error","errorMessage"=>Constant::ALL_CLASSES_SELECTED_ERROR_MSG));
            return $output;
        }
        foreach ($objectList as $objectId) {
            $outLinks = $graphStructure[$objectId]["outLinks"];
            $inLinks = $graphStructure[$objectId]["inLinks"];
            $isConnected = "false";
            if ($outLinks != Constant::TERMINATED_NODE) {
                foreach ($outLinks as $outLink) {
                    $nextObjectId = $outLink["toObjectId"];
                    if (in_array($nextObjectId, $objectList)) {
                        $isConnected = "true";
                        break;
                    }
                    $nextOutLinks = $graphStructure[$nextObjectId]["outLinks"];
                    if ($nextOutLinks != Constant::TERMINATED_NODE) {
                        foreach ($nextOutLinks as $nextOutLink) {
                            if (in_array($nextOutLink["toObjectId"], $objectList)) {
                                $isConnected = "true";
                                break;
                            }
                        }
                    }
                }
            }
            if ($isConnected == "true") {
                continue;
            }
            if ($inLinks != Constant::TERMINATED_NODE) {
                foreach ($inLinks as $inLink) {
                    $previousObjectId = $inLink["fromObjectId"];
                    if (in_array($previousObjectId, $objectList)) {
                        $isConnected = "true";
                        break;
                    }
                    $previousOutLinks = $graphStructure[$previousObjectId]["inLinks"];
                    if ($previousOutLinks != Constant::TERMINATED_NODE) {
                        foreach ($previousOutLinks as $previousOutLink) {
                            if (in_array($previousOutLink["fromObjectId"], $objectList)) {
                                $isConnected = "true";
                                break;
                            }
                        }
                    }
                }
            }
            if ($isConnected == "false") {
                $objectNode = CallGraphService::selectFromObjectNodeByObjectID($objectId)[0];
                $erroMessage = Constant::UNRELATED_CLASSES_SELECTED_ERROR_MSG.$objectNode["objectName"].":".$objectNode["baseIdentifier"].". Continue?";
                array_push($output,array("isSuccess"=>"warning","errorMessage"=>$erroMessage));
                return $output;
            }
        }
        $output["isSuccess"] = "success";
        return $output;
    }

    private static function getGraphStructure($graphId)
    {
        $graphStructure = array();
        $objectList = CallGraphService::selectFromObjectNodeByCallGraphId($graphId);
        foreach ($objectList as $objectNode) {
            $objectId = $objectNode["objectId"];
            $outMessages = CallGraphService::selectFromMessageByFromObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
            $inMessages = CallGraphService::selectFromMessageByToObjectIDAndMessageType($objectId, Constant::CALLING_MESSAGE_TYPE);
            if (count($outMessages) == 0) {
                $outMessages = Constant::TERMINATED_NODE;
            }
            if (count($inMessages) == 0) {
                $inMessages = Constant::TERMINATED_NODE;
            }
            $graphStructure[$objectId] = array(
                "inLinks" => $inMessages,
                "outLinks" => $outMessages
            );
        }
        return $graphStructure;
    }
}
?>