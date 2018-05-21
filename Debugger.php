<?php
    //  require_once "PHP/CallGraphService.php";
    //  require_once "PHP/ClassDiagramService.php";
    //  $conn = Database::connectToDB();
    //  Database::dropDatabase($conn, "CallGraph");
    //  Database::dropDatabase($conn, "ClassDiagram");
    //  CallGraphService::initialCallGraphDatabase($conn);
    //  ClassDiagramService::initialClassDiagramDatabase($conn);
    //  $conn->close();
    require_once "Page/DiagramMgrService.php";
    DiagramMgrService::renameCallGraph("getGPAX Simple.xml","getGPAX.xml");
?>
