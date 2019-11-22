<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
require_once "$root/php/database/CallGraphProcessingService.php";

 SDProcessor::readSequenceDiagramFile("test", "$root/SequenceDiagrams/1574255330_getGPAX.xml");


?>
