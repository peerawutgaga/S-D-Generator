<?php
    //require_once "PHP/XMLProcessor/SDProcessor.php";
    //SDProcessor::readSequenceDiagram("getGPAX","./Example XML/Old Testsuite/getGPAX Traditional.xml");
   // SDProcessor::readSequenceDiagram("openCourse","./Example XML/Register System/OpenCourse Traditional.xml");
    require_once "php/xmlprocessor/CDProcessor.php";
    // CDProcessor::readClassDiagram("Register Simple","./Example XML/Old Testsuite/Register Simple.xml");
    //CDProcessor::readClassDiagram("Register Simple","./Example XML/Register System/Register Simple.xml");
    CDProcessor::readClassDiagram("Register Traditioanl","./Example XML/Register System/Register Traditional.xml");
    // CDProcessor::readClassDiagram('Register Traditional','./Example XML/Old Testsuite/Register Traditional.xml');
    // require_once "PHP/Database/ClassDiagramService.php";
    // require_once "Diagram/ClassDiagram/Method.php";
    // use ClassDiagram\Method;
    // $methodObject = new Method("methodID","methodName");
    // $methodObject->setReturnType("returnType");
    // $methodObject->setReturnTypeModifier("[]");
    // $methodObject->setVisibility("Visible");
    // $methodObject->setIsStatic(1);
    // $methodObject->setIsAbstract(1);
    // ClassDiagramService::insertToMethodTable(1, "test", $methodObject);
//     require_once "Page/SourceCodeGen/SourceCodeGenerator.php";
//    echo SourceCodeGenerator::initial(1,30,"HW5IJfqGAqACJQe_","driver","PHP");

?>
