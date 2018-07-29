<?php
    require_once "PHP/XMLProcessor/SDProcessor.php";
    // $diagram = realpath($_SERVER["DOCUMENT_ROOT"])."/Diagram/SequenceDiagram/";
    // include $Diagram."CallGraph.php";
    // $callGraph = new CallGraph($fileName);
    // $callGraph->setFileTarget($targetFile);
    SDProcessor::readSequenceDiagram("getGPAX","./Example XML/Old Testsuite/getGPAX Traditional.xml");
    // include "Diagram/SequenceDiagram/CallGraph.php";
    // use SequenceDiagram\CallGraph;
    // echo 3;  
    // $callGraph = new CallGraph("a","b");
    // echo 5;
    // $callGraph->setGraphName("aaa");
    // $callGraph->setFileTarget("aaa");
    // echo 7;
?>
