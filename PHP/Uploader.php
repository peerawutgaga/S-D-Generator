<?php
    require_once "CallGraphService.php";
    require_once "ClassDiagramService.php";
    require_once "CDProcessor.php";
    require_once "SDProcessor.php";

    if(isset($_POST['SDSubmit'])){
        Uploader::upload("../Sequence Diagrams/", "SDFile");
    }
    else if(isset($_POST['CDSubmit'])){
        Uploader::upload("../Class Diagrams/", "CDFile");
    }
    class Uploader{
        public static function upload($target_dir, $diagramType){
            $target_file = $target_dir . basename($_FILES[$diagramType]["name"]);
            $fileName = basename($_FILES[$diagramType]["name"]);
            $xmlFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if (file_exists($target_file)) {
                Script::alert("File already exist.");
                return;
            }
            if($xmlFileType != "xml" ) {
                Script::alert("Only XML file is allowed");
                return;
            }
            if (move_uploaded_file($_FILES[$diagramType]["tmp_name"], $target_file)) {
                if($diagramType == "SDFile"){
                    SDProcessor::readSequenceDiagram($fileName, $target_file);
                }else{
                    CDProcessor::readClassDiagram($fileName,$target_file);
                }
                Script::alert("The file ". basename( $_FILES[$diagramType]["name"]). " has been uploaded.");
            } else {
                Script::alert("Upload failed.");
            }
        }
    }
    
?> 
