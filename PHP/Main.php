<?php
    if(isset($_POST['SDSubmit']))
    {
        upload("../Class Diagrams/", "SDFile");
    }
    else if(isset($_POST['CDSubmit'])){
        upload("../Sequence Diagrams/", "CDFile");
    }
    function upload($target_dir, $diagramType)
    {
        $target_file = $target_dir . basename($_FILES[$diagramType]["name"]);
        $uploadOk = 1;
        $xmlFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check file size
        if($xmlFileType != "xml") {
            echo "Sorry, only XML files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES[$diagramType]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES[$diagramType]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
?> 