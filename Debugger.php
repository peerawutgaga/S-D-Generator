<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
//require_once "$root/php/xmlprocessor/cdprocessor/CDProcessor.php";
//require_once "$root/php/pages/CodeProperties.php";
require_once $root."/php/database/CallGraphService.php";

$result = CallGraphService::selectAllFromGraph();
Script::printObject($result);
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1576983796_getGPAX.xml");
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1577451562_payByCreditCard.xml");
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1576983827_openCourse.xml");
 //CDProcessor::readClassDiagramFile("test", "$root/ClassDiagrams/1577199249_register_system.xml");
 //initialSDSelection();
?>
