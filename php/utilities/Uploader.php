<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root."/php/utilities/Script.php";
    //require_once $root."/php/xmlprocessor/cdprocessor/CDProcessor.php";
    require_once $root."/php/xmlprocessor/sdprocessor/SDProcessor.php";
    if(isset($_POST["submitFile"])){    
        if($_FILES["SDFile"] != null){
            Uploader::uploadSD();
        }else if($_FILES["CDFile"] != null){
            Uploader::uploadCD();
       }       
    }
    class Uploader{
        
        private static $fileName;
        private static $target_file;
        public static function upload($target_dir,$diagramFileType){
            $currentTime = time();    
            self::$fileName = basename($_FILES[$diagramFileType]["name"]);     
            self::$target_file = $target_dir .$currentTime."_".self::$fileName;
            $xmlFileType = strtolower(pathinfo(Self::$target_file,PATHINFO_EXTENSION));
            if (file_exists(self::$target_file)) {
                Script::alert("File already exist.");
                return false;
            }
            if($xmlFileType != "xml" ) {
               Script::alert("Only XML file is allowed");
                return false;
            }
            return move_uploaded_file($_FILES[$diagramFileType]["tmp_name"], self::$target_file);
        }
        public static function uploadSD(){
            $isUploadSuccess = Uploader::upload("../../SequenceDiagrams/","SDFile");
            if($isUploadSuccess){ 
                SDProcessor::readSequenceDiagramFile(self::$fileName, self::$target_file);
            }else{
                Script::alert("Upload failed.");
            }
            //Script::returnTo("../../index.php");
        }
        public static function uploadCD(){
            $isUploadSuccess = Uploader::upload("../../ClassDiagrams/","CDFile");
            if($isUploadSuccess){
                //CDProcessor::readClassDiagramFile(Self::$fileName, Self::$target_file);
            }else{
                Script::alert("Upload failed.");
            }
            Script::returnTo("../../index.php");
        }
    }
    
?> 