<?php
    require "Database.php";
    if(isset($_POST['SDSubmit']))
    {
        upload("../Sequence Diagrams/", "SDFile");
    }
    else if(isset($_POST['CDSubmit'])){
        upload("../Class Diagrams/", "CDFile");
    }
    function upload($target_dir, $diagramType){
        $target_file = $target_dir . basename($_FILES[$diagramType]["name"]);
        $uploadOk = 1;
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
            if($diagramType == "SDFile")
            {
                createCallGraph($target_file);
            }
            alert("The file ". basename( $_FILES[$diagramType]["name"]). " has been uploaded.");
        } else {
            alert("Upload failed.");
        }
    }
    function alert($message){
        echo "<script type='text/javascript'>
            alert('$message');
            window.location.href='../index.html';
        </script>";
    }
    function createCallGraph($target_file){

    }
    
?> 
