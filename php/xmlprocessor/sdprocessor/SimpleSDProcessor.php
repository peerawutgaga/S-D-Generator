<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/database/CallGraphService.php";
require_once "$root/php/database/CallGraphProcessingService.php";
require_once "$root/php/utilities/Script.php";
require_once "$root/php/utilities/Logger.php";

class SimpleSDProcessor
{

    private static $graphID;

    private static $xml;

    const callingMessageType = "CALLING";

    const returnMessageType = "RETURN";

    const createMessageType = "CREATE";

    const detroyMessageType = "DESTROY";

    public static function processSimpleSD($xml, $graphID)
    {
        self::$graphID = $graphID;
        self::$xml = $xml;
        $objectList = $xml->Models->Frame->ModelChildren;
        $messageList = $xml->Models->ModelRelationshipContainer->ModelChildren->ModelRelationshipContainer->ModelChildren->Message;
        CallGraphProcessingService::cleanProcessingDatabase();
        self::identifyNode($objectList);
        self::identifyMessage($messageList);
        self::identifyGuardCondition();
    }

    private static function identifyNode($objectList)
    {
        foreach ($objectList->children() as $objectNode) {
            if ($objectNode->getName() == 'InteractionLifeLine') {
                $objectName = $objectNode['Name'];
                $baseIdentifier = $objectNode->BaseClassifier->Class['Name'];
                $objectId = CallGraphService::insertIntoObjectNode(self::$graphID, $objectName, $baseIdentifier);
                if ($objectId != null) {
                    CallGraphProcessingService::insertIntoProcessingObject($objectId, $objectNode['Id']);
                }
            } else if ($objectNode->getName() == 'InteractionActor') {
                $objectName = $objectNode['Name'];
                $objectId = CallGraphService::insertIntoObjectNode(self::$graphID, $objectName, "Actor");
                if ($objectId != null) {
                    CallGraphProcessingService::insertIntoProcessingObject($objectId, $objectNode['Id']);
                }
            } else if ($objectNode->getName() == 'Gate') {
                $gateIdStr = $objectNode["Id"];
            }
        }
    }

    private static function identifyMessage($messageList)
    {
        foreach ($messageList as $message) {
            $actionType = $message->ActionType->children()[0]["Name"];
            $fromObjectIdStr = $message["EndRelationshipFromMetaModelElement"];
            $fromObjectId = CallGraphProcessingService::selectObjectIdByObjectIdStr($fromObjectIdStr)[0];
            $toObjectIdStr = $message["EndRelationshipToMetaModelElement"];
            $toObjectId = CallGraphProcessingService::selectObjectIdByObjectIdStr($toObjectIdStr)[0];
            if ($fromObjectId == null || $toObjectId == null) {
                Script::alert("Invalid XML file");
                $eventPayload = print_r($fromObjectId, true) . "\n" . print_r($toObjectId, true);
                Logger::logInternalError("SimpleSDProcessor", $eventPayload);
                CallGraphService::deleteFromGraphByCallGraphId(self::$graphID);
                return;
            }
            if ($actionType == "Call") {
                $operationId = $message->ActionType->ActionTypeCall["Operation"];
                if (isset($operationId)) {
                    $messageName = self::$xml->xpath("//Operation[@Id='$operationId']")[0]["Name"];
                    $messageId = CallGraphService::insertIntoMessage($fromObjectId["objectId"], $toObjectId["objectId"], $messageName, self::callingMessageType);
                    CallGraphProcessingService::insertIntoProcessingMessage($messageId, $message["Id"], $message["ReturnMessage"], $fromObjectIdStr, $toObjectIdStr);
                    if ($message["ReturnMessage"] != null) {
                        self::handleReturnMessage($messageId, $message, $operationId, $fromObjectId["objectId"], $toObjectId["objectId"]);
                    }
                    self::identifyArgument($messageId, $operationId);
                } else {
                    $messageName = $message["Name"];
                    if ($message["Type"] == "Create Message") {
                        $messageId = CallGraphService::insertIntoMessage($fromObjectId["objectId"], $toObjectId["objectId"], $messageName, self::createMessageType);
                    }  else {
                        $messageId = CallGraphService::insertIntoMessage($fromObjectId["objectId"], $toObjectId["objectId"], $messageName, self::callingMessageType);
                    }
                    CallGraphProcessingService::insertIntoProcessingMessage($messageId, $message["Id"], $message["ReturnMessage"], $fromObjectIdStr, $toObjectIdStr);
                }
                if (isset($message->ActionType->ActionTypeCall["Guard"])) {
                    $statement = $message->ActionType->ActionTypeCall["Guard"];
                    CallGraphService::insertIntoGuardCondition($messageId, $statement);
                }
            }else if ($actionType == "Destroy") {
                $messageName = $message["Name"];
                $messageId = CallGraphService::insertIntoMessage($fromObjectId["objectId"], $toObjectId["objectId"], $messageName, self::detroyMessageType);
                CallGraphProcessingService::insertIntoProcessingMessage($messageId, $message["Id"], $message["ReturnMessage"], $fromObjectIdStr, $toObjectIdStr);
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
        $messageId = CallGraphService::insertIntoMessage($toObjectId, $fromObjectId, $messageName, self::returnMessageType);
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
                $messageId = CallGraphProcessingService::selectMessageIdByMessageIdStr($messageIdStr);
                CallGraphService::insertIntoGuardCondition($messageId[0]["messageId"], $statement);
            }
        }
    }
    private static function convertGateToClass($gateIdStr){
        
    }
}
?>