<?php
    require_once "../PHP/SourceCodeService.php";
    require_once "../PHP/CallGraphService.php";
    require_once "../PHP/CDProcessor.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $filename = $_POST['filename'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    echo $graphID." ".$diagramID." ".$classID." ".$filename." ".$sourceType." ".$sourceLang;
?>