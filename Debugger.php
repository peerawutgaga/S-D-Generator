<?php
    // require_once "./PHP/CDProcessor.php";
    // require_once "./PHP/SDProcessor.php";
    //require_once "./Page/CreateSourceCode.php";
    require_once "./PHP/CallGraphService.php";
    //CDProcessor::readClassDiagram("Test1",'Example XML/CourseVille Traditional.xml');
    //CDProcessor::readClassDiagram("Test2",'Example XML/CourseVille Simple.xml');
    // SDProcessor::readSequenceDiagram("Test3","Example XML/project.xml");
    //SourceCodeGenerator::initialFile( 1,1,"gzxIJfqGAqACJQew", "aaa", "stub", "Java");
    $conn = Database::connectToDB();
    //Database::dropDatabase($conn,'callGraph');
    CallGraphService::initialCallGraphDatabase($conn);
    CallGraphService::insertToNodeTable($conn, 1, 'a', 'aaa');
    CallGraphService::insertToNodeTable($conn, 1, 'a', 'aaa');
    $conn->close();
?>