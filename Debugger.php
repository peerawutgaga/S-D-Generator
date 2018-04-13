<?php
    //  require_once "Page/SourceCodeGenerator.php";
    // require_once "PHP/LocalFileManager.php";
    require_once "PHP/SDProcessor.php";
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","stub","Java");
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","driver","Java"); //GPAXCalculator
    // SourceCodeGenerator::initial(1,1,"HW5IJfqGAqACJQe_","driver","Java"); //EnrollmentRepository
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","stub","PHP");
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","driver","PHP"); //GPAXCalculator
    // SourceCodeGenerator::initial(1,1,"HW5IJfqGAqACJQe_","driver","Java"); //EnrollmentRepository
    //LocalFileManager::zip("EnrollmentStub.java,EnrollmentStub.php,StudentStub.java");
    SDProcessor::readSequenceDiagram("Register Course Traditional", "Example XML/Register Course Traditional.xml");

?>
