<?php
    //require_once "PHP/XMLProcessor/SDProcessor.php";
    //SDProcessor::readSequenceDiagram("getGPAX","./Example XML/Old Testsuite/getGPAX Traditional.xml");
    require_once "PHP/XMLProcessor/CDProcessor.php";
    CDProcessor::readClassDiagram("Register Simple","./Example XML/Old Testsuite/Register Simple.xml");
    // CDProcessor::readClassDiagram('Register Simple','./Example XML/Old Testsuite/Register Traditional.xml');
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
?>
