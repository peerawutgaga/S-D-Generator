<?php
    //require_once "./PHP/CDProcessor.php";
    // require_once "./PHP/SDProcessor.php";
     require_once "./Page/CreateSourceCode.php";
    // $conn = Database::connectToDB();
    // Database::dropDatabase($conn,'SourceCode');
    // $conn->close();
    SourceCodeGenerator::createSourceCode(1,1,'gzxIJfqGAqACJQew','test1','stub','Java');
    SourceCodeGenerator::createSourceCode(1,1,'gzxIJfqGAqACJQew','test2','stub','PHP');
    SourceCodeGenerator::createSourceCode(1,1,'gzxIJfqGAqACJQew','test3','driver','Java');
    SourceCodeGenerator::createSourceCode(1,1,'gzxIJfqGAqACJQew','test4','driver','PHP');
    //SDProcessor::readSequenceDiagram('test','./Example XML/getGPAX Simple.xml');
    //CDProcessor::readClassDiagram('test','./Example XML/Register Traditional.xml');
?>