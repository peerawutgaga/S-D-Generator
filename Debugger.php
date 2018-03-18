<?php
     require_once "./PHP/CDProcessor.php";
    // require_once "./PHP/SDProcessor.php";
     require_once "./Page/CreateSourceCode.php";
    // $conn = Database::connectToDB();
    // Database::dropDatabase($conn,'SourceCode');
    // $conn->close();
    // SourceCodeGenerator::createSourceCode(1,1,'gzxIJfqGAqACJQew','test2','driver','PHP');
    SourceCodeGenerator::createSourceCode(1,1,'HW5IJfqGAqACJQe_','test3','driver','Java');
    SourceCodeGenerator::createSourceCode(1,1,'hCd5JfqGAqACJQiu','test4','driver','PHP');
    //SDProcessor::readSequenceDiagram('test','./Example XML/getGPAX Simple.xml');
    // CDProcessor::readClassDiagram('Register Simple','./Example XML/Register Simple.xml');
    // CDProcessor::readClassDiagram('Register Traditional','./Example XML/Register Traditional.xml');
    // CDProcessor::readClassDiagram('test1','./Example XML/CourseVille Simple.xml');
    // CDProcessor::readClassDiagram('test2','./Example XML/CourseVille Traditional.xml');
?>