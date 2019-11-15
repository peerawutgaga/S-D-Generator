<?php
require_once "./php/database/ClassDiagramService.php";
require_once "./php/database/CallGraphService.php";
require_once "./php/database/SourceCodeService.php";
//$result = ClassDiagramService::insertIntoInheritance(1,2);
//$result = CallGraphService::selectMessageByFromAndToObjectId(1, 2);
$result = SourceCodeService::updateSourceCodeFileSetFilePayloadByFileId("test",1);
//Script::consoleLog($result);
Script::printObject($result);
?>
