<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/php/database/CallGraphService.php';
require_once $root . '/php/utilities/Script.php';
require_once $root . '/php/utilities/Logger.php';
require_once $root . '/php/utilities/Constant.php';

class SDProcessor
{

    private static $callGraphId;

    private static $xml;

    private static $objectList;

    private static $messageList;
    
    private static $isReferred;

    public static function readSequenceDiagramFile($filename, $filePath)
    {
        self::$xml = simplexml_load_file($filePath);
        if (self::$xml === false) {
            $errorMessage = "Failed loading XML: " . "<br>";
            foreach (libxml_get_errors() as $error) {
                $errorMessage = $errorMessage . "\n" . $error->message;
            }
            Logger::logDatabaseError("SDProcessor", $errorMessage);
            return;
        }
        if (self::$xml['Xml_structure'] == 'simple') {
            self::$callGraphId = CallGraphService::insertIntoCallGraph($filename, $filePath);
            self::processSequenceDiagram();
            if(self::$isReferred){
                Script::alert("This sequence diagram refers to other sequence diagrams. Please upload those diagrams and link them together by XML Manager");
            }
            
        } else {
            Script::alert("Traditional XML format does not support by the tool.");
        }
    }

    private static function processSequenceDiagram()
    {
        // Assume that the first interaction diagram is the primary diagram.
        $diagram = self::$xml->Diagrams->InteractionDiagram[0];
        $frame = self::$xml->Models->Frame[0];
        $messages = self::$xml->Models->ModelRelationshipContainer->ModelChildren->ModelRelationshipContainer->ModelChildren;
        $connectors = $diagram->Connectors;
        self::$objectList = array();
        self::$messageList = array();
        self::$isReferred = false;
        self::identifyObjectNode($diagram, $frame);
        self::identifyMessage($messages, $connectors);
        self::identifyGuardCondition();
    }

    private static function identifyObjectNode($diagram, $frame)
    {
        foreach ($diagram->Shapes->children() as $objectNode) {
            $objectName = $objectNode["Name"];
            $modelId = $objectNode["Model"]; // A model id is an object id in model section
            if ($objectNode->getName() == "InteractionLifeLine") {
                $baseIdentifier = $frame->xpath("./ModelChildren/InteractionLifeLine[@Id='$modelId']/BaseClassifier/Class")[0]["Name"];
                self::insertObjectNode($modelId, $objectName, $baseIdentifier);
            } else if ($objectNode->getName() == "InteractionActor") {
                self::insertObjectNode($modelId, $objectName, Constant::ACTOR_TYPE);
            } else if ($objectNode->getName() == "InteractionOccurrence") {
                $gateIdStr = $objectNode->GateShapeUIModelIds->Value["Value"];
                $gateModelId = $diagram->xpath("./Shapes/Gate[@Id='$gateIdStr']")[0]["Model"];
                self::insertObjectNode($gateModelId, $objectName, Constant::REF_DIAGRAM_TYPE);
                self::$isReferred = true;
            }
        }
    }

    private static function insertObjectNode($objectIdStr, $objectName, $baseIdentifier)
    {
        $objectId = CallGraphService::insertIntoObjectNode(self::$callGraphId, $objectName, $baseIdentifier);
        if ($objectId != - 1) {
            self::$objectList[(string) $objectIdStr] = $objectId;
        }
    }

    private static function identifyMessage($messages, $connectors)
    {
        foreach ($connectors->children() as $connector) {
            $messageIdStr = $connector["Model"];
            $message = $messages->xpath("./Message[@Id='$messageIdStr']")[0];
            $fromObjectId = self::$objectList[(string) $message["EndRelationshipFromMetaModelElement"][0]];
            $toObjectId = self::$objectList[(string) $message["EndRelationshipToMetaModelElement"][0]];
            $messageType = $message["Type"];
            $returnMessageIdStr = $message["ReturnMessage"];
            if ($fromObjectId == null || $toObjectId == null) {
                Script::alert("Invalid XML file");
                $eventPayload = print_r($fromObjectId, true) . "\n" . print_r($toObjectId, true);
                Logger::logInternalError("SDProcessor", $eventPayload);
                CallGraphService::deleteFromGraphByCallGraphId(self::$graphID);
                return;
            }
            if ($messageType == "Message" || $messageType == "Recursive Message") {
                $actionType = $message->ActionType->children()[0]["Name"];
                if ($actionType == "Call") {
                    $operationId = $message->ActionType->ActionTypeCall["Operation"];
                    if (isset($operationId)) {
                        $messageName = self::$xml->xpath("//Operation[@Id='$operationId']")[0]["Name"];
                    } else {
                        $messageName = $message["Name"];
                    }
                    $messageId = CallGraphService::insertIntoMessage($fromObjectId, $toObjectId, $messageName, Constant::CALLING_MESSAGE_TYPE);
                    self::$messageList[(string) $messageIdStr] = $messageId;
                    if ($returnMessageIdStr != null) {
                        self::handleReturnMessage($messageId, $message, $operationId, $fromObjectId, $toObjectId);
                    }
                    self::identifyArgument($messageId, $operationId);
                    if (isset($message->ActionType->ActionTypeCall["Guard"])) {
                        $statement = $message->ActionType->ActionTypeCall["Guard"];
                        CallGraphService::insertIntoGuardCondition($messageId, $statement);
                    }
                } else if ($actionType == "Destroy") {
                    $messageName = $message["Name"];
                    $messageId = CallGraphService::insertIntoMessage($fromObjectId, $toObjectId, $messageName, Constant::DESTROY_MESSAGE_TYPE);
                    self::$messageList[(string) $messageIdStr] = $messageId;
                }
            } else if ($messageType == "Create Message") {
                $messageName = $message["Name"];
                $messageId = CallGraphService::insertIntoMessage($fromObjectId, $toObjectId, $messageName, Constant::CREATE_MESSAGE_TYPE);
                self::$messageList[(string) $messageIdStr] = $messageId;
            }
        }
    }

    private static function handleReturnMessage($parentMsgId, $message, $operationId, $fromObjectId, $toObjectId)
    {
        $returnMessageId = $message["ReturnMessage"];
        $returnType = self::$xml->xpath("//Operation[@Id='$operationId']")[0]->ReturnType;
        $messageName = self::$xml->xpath("//Message[@Id='$returnMessageId']")[0]["Name"];
        $isObject = 0;
        if (isset($returnType->Class)) {
            $isObject = 1;
            $dataType = $returnType->Class["Name"];
        } else {
            $dataType = $returnType->DataType["Name"];
        }
        $messageId = CallGraphService::insertIntoMessage($toObjectId, $fromObjectId, $messageName, Constant::RETURN_MESSAGE_TYPE);
        CallGraphService::insertIntoReturnMessage($messageId, $dataType, $isObject, $parentMsgId);
    }

    private static function identifyArgument($messageId, $operationId)
    {
        $operation = self::$xml->xpath("//Operation[@Id='$operationId']")[0];
        if (isset($operation->ModelChildren->Parameter)) {
            $seqIdx = 1;
            foreach ($operation->ModelChildren->Parameter as $argument) {
                $isObject = 0;
                $arguName = $argument["Name"];
                if (isset($argument->Type->Class)) {
                    $isObject = 1;
                    $dataType = $argument->Type->Class["Name"];
                } else {
                    $dataType = $argument->Type->DataType["Name"];
                }
                CallGraphService::insertIntoArgument($messageId, $arguName, $seqIdx, $dataType, $isObject);
                $seqIdx ++;
            }
        }
    }

    private static function identifyGuardCondition()
    {
        $combinedFragments = self::$xml->xpath("//CombinedFragment[@OperatorKind='alt']");
        foreach ($combinedFragments as $combinedFragment) {
            $operands = $combinedFragment->ModelChildren->InteractionOperand;
            foreach ($operands as $operand) {
                $messageIdStr = $operand->Messages->Message[0]["Idref"];
                $statement = $operand->Guard->InteractionConstraint[0]["Constraint"];
                $messageId = self::$messageList[(string) $messageIdStr];
                CallGraphService::insertIntoGuardCondition($messageId, $statement);
            }
        }
    }
}
?>