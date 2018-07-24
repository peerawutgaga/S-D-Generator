<?php
    require_once "PHP/Database/CallGraphService.php";
    include "Diagram/SequenceDiagram/CallGraph.php";
    use SequenceDiagram\CallGraph;
    echo 3;  
    $callGraph = new CallGraph("a","b");
    echo 5;
    $callGraph->setGraphName("aaa");
    $callGraph->setFileTarget("aaa");
    echo 7;
    CallGraphService::insertToGraphTable($callGraph);
    echo 9
?>
