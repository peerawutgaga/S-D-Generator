<?php
     require_once "Page/SourceCodeGenerator.php";
     $conn = Database::connectToDB();
     Database::dropDatabase($conn,"SourceCode");
     $conn->close();
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","stub","Java");
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","driver","Java"); //GPAXCalculator
    // SourceCodeGenerator::initial(1,1,"HW5IJfqGAqACJQe_","driver","Java"); //EnrollmentRepository
     SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","stub","PHP");
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","driver","PHP"); //GPAXCalculator
    // SourceCodeGenerator::initial(1,1,"HW5IJfqGAqACJQe_","driver","Java"); //EnrollmentRepository
?>