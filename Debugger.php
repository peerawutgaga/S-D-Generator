<?php
     require_once "Page/SourceCodeGenerator.php";
     $conn = Database::connectToDB();
     Database::dropDatabase($conn,"SourceCode");
     $conn->close();
     SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","stub","Java");
    //  SourceCodeGenerator::initial(1,1,"gzxIJfqGAqACJQew","driver","Java");
?>