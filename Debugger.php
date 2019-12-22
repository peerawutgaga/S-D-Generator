<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
require_once "$root/php/xmlprocessor/cdprocessor/CDProcessor.php";


 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1574255330_getGPAX.xml");
 //SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1576983827_openCourse.xml");
 CDProcessor::readClassDiagramFile("test", "$root/ClassDiagrams/1576999749_register_system.xml");
?>
