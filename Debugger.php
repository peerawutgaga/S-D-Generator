<?php
    require_once "./PHP/CDProcessor.php";
    require_once "./PHP/SDProcessor.php";
    //CDProcessor::readClassDiagram("Test1",'Example XML/CourseVille Traditional.xml');
    //CDProcessor::readClassDiagram("Test2",'Example XML/CourseVille Simple.xml');
    SDProcessor::readSequenceDiagram("Test3","Example XML/project.xml");
?>