<?php
    // require_once "./PHP/CDProcessor.php";
    // require_once "./PHP/SDProcessor.php";
    require_once "./Page/CreateSourceCode.php";
    $conn = Database::connectToDB();
    Database::dropDatabase($conn,'SourceCode');
    $conn->close();
    SourceCodeGenerator::createSourceCode(1,1,'ixBIJfqGAqACJQeZ','test','stub','Java');
    SourceCodeGenerator::createSourceCode(1,1,'ixBIJfqGAqACJQeZ','test','driver','PHP');
?>