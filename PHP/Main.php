<?php
    require_once "CallGraphService.php";
    require_once "ClassDiagramService.php";

    if(isset($_POST['SDSubmit'])){
        upload("../Sequence Diagrams/", "SDFile");
    }
    else if(isset($_POST['CDSubmit'])){
        upload("../Class Diagrams/", "CDFile");
    }
    function upload($target_dir, $diagramType){
        $target_file = $target_dir . basename($_FILES[$diagramType]["name"]);
        $xmlFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if (file_exists($target_file)) {
            alert("File already exist.");
            return;
        }
        if($xmlFileType != "xml" ) {
            alert("Only XML file is allowed");
            return;
        }
        if (move_uploaded_file($_FILES[$diagramType]["tmp_name"], $target_file)) {
            if($diagramType == "SDFile"){
                createCallGraph($target_file);
            }else{
                processClassDiagram($target_file);
            }
           alert("The file ". basename( $_FILES[$diagramType]["name"]). " has been uploaded.");
        } else {
            alert("Upload failed.");
        }
    }
    function createCallGraph($target_file){
        $xml = simplexml_load_file($target_file) or die("Error: cannot create object");
    }
    function processClassDiagram($target_file){
        $xml = simplexml_load_file($target_file) or die("Error: cannot create object");
    }
    consoleLog('test');
?> 
