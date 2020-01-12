<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
//require_once "$root/php/xmlprocessor/cdprocessor/CDProcessor.php";
require_once "$root/php/pages/DiagramSelection.php";
require_once "$root/php/pages/CodeEditor.php";
//require_once $root."/php/database/CallGraphService.php";
require_once $root . '/php/database/ClassDiagramService.php';
require_once $root."/php/sourcecode/SourceCodeGenerator.php";
require_once $root."/php/sourcecode/java/JavaGenerator.php";
require_once $root."/php/utilities/Script.php";
require_once $root."/php/utilities/DataGenerator.php";
require_once $root."/php/utilities/LocalFileManager.php";

//echo DataGenerator::getRandomDoubleWithBound(0,10,3);
//SourceCodeGenerator::createCode(8, "93", "JAVA");
//$result = JavaGenerator::getClassesAndMethod("PaymentType", "pay");
//Script::printObject($result);
//DiagramSelection::checkReferenceDiagram(130);
//DiagramSelection::checkClassesRelation(133, "85,86,88");
//$childClassId = ClassDiagramService::selectChildIdFromInheritanceBySuperClassId(166);
//$childClassId = DataGenerator::convertArrayOfArrayToSingleStringByKey($childClassId, 0);
//Script::printObject($childClassId);
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1576983796_getGPAX.xml");
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1577451562_payByCreditCard.xml");
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1576983827_openCourse.xml");
 //CDProcessor::readClassDiagramFile("test", "$root/ClassDiagrams/1577199249_register_system.xml");
// LocalFileManager::zip("107,108,109,110");
CodeEditor::openFile(115);
?>
