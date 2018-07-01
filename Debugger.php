<?php
    require_once "PHP/XMLProcessor/SDProcessor.php";
    $fileName = "getGPAX Traditional.xml";
    $target_file = "./Example XML/Old Testsuite/getGPAX Traditional.xml";
    SDProcessor::readSequenceDiagram($fileName, $target_file);
?>
