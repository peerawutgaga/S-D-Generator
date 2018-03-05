<?php
    require_once "SDProcessor.php";
    SDProcessor::readSequenceDiagram('Test1',"../Example XML/getGPAX Simple.xml");
    SDProcessor::readSequenceDiagram('Test2',"../Example XML/getGPAX Traditional.xml");
    //Database::connectToDBusingPDO('callGraph');
?>