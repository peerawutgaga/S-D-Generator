<?php
    require_once "./PHP/CallGraphService.php";
    $conn = Database::connectToDB();
    Database::dropDatabase($conn,'callGraph');
    CallGraphService::initialCallGraphDatabase($conn);
    CallGraphService::insertToNodeTable($conn,1,aaa,bbb);
    CallGraphService::insertToNodeTable($conn,1,bbb,ccc);
    CallGraphService::insertToNodeTable($conn,2,aaa,bbb);
    CallGraphService::insertToNodeTable($conn,2,bbb,ccc);
    CallGraphService::insertToNodeTable($conn,1,aaa,bbb);
    CallGraphService::insertToNodeTable($conn,2,aaa,ccc);
    CallGraphService::insertToMessageTable($conn,1,aaa,bbb,ccc,ddd);
    CallGraphService::insertToMessageTable($conn,1,bbb,eee,ccc,ddd);
    CallGraphService::insertToMessageTable($conn,1,aaa,bbb,ccc,ddd);
    CallGraphService::insertToMessageTable($conn,2,aaa,bbb,ccc,ddd);
    CallGraphService::insertToMessageTable($conn,2,bbb,bbb,ccc,ddd);
    $conn->close();
?>