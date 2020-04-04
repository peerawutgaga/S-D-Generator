<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
require_once "$root/php/xmlprocessor/cdprocessor/CDProcessor.php";
require_once "$root/php/pages/DiagramManagerPage.php";
require_once "$root/php/pages/SourceCodeManagerPage.php";
require_once $root."/php/utilities/Script.php";
require_once $root . '/php/sourcecode/SourceCodeGenerator.php';
require_once "$root/php/pages/DiagramSelectionPage.php";
require_once $root . '/php/database/CallGraphService.php';
require_once $root . '/php/database/ClassDiagramService.php';

//DiagramSelectionPage::checkClassesRelation(135, "97,99");
//SourceCodeManagerPage::getFileList();
SDProcessor::readSequenceDiagramFile("multiple_reference.xml", "D:\\Development\\S-D-Generator\\SequenceDiagrams\\1585986764_multiple_reference.xml");
//CDProcessor::readClassDiagramFile("Bank.xml", "D:\\Development\\S-D-Generator\\ClassDiagrams\\1580917409_Banking_Class_Diagram.xml");
//SourceCodeGenerator::createCode(25,24, "128", "JAVA");
//Script::printObject(CallGraphService::selectOtherObjectNodeInCallGraphByBaseIdentifier('25', '127', 'C1'));
//Script::printObject(ClassDiagramService::selectClassByDiagramIdAndObjectBase(6,"TransactionController"));
?>
