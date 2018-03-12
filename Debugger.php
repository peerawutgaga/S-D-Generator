<?php
    //require_once "./PHP/CDProcessor.php";
    // require_once "./PHP/SDProcessor.php";
     require_once "./Page/CreateSourceCode.php";
    // $conn = Database::connectToDB();
    // Database::dropDatabase($conn,'SourceCode');
    // $conn->close();
    SourceCodeGenerator::createSourceCode(1,1,'HW5IJfqGAqACJQe_','test','driver','Java');
    // SourceCodeGenerator::createSourceCode(2,1,'r7IcwEaGAqACJQoX','test','driver','PHP');
    //SDProcessor::readSequenceDiagram('test','./Example XML/getGPAX Simple.xml');
    //CDProcessor::readClassDiagram('test','./Example XML/Register Traditional.xml');
?>