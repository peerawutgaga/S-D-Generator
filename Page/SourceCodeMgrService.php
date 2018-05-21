<?php
     $root = realpath($_SERVER["DOCUMENT_ROOT"]);
     require_once "$root/PHP/SourceCodeService.php";
     require_once "$root/PHP/LocalFileManager.php";
     $method = $_POST['method'];
     if($method == "getList"){
        echo SourceCodeMgrService::getSourceCodeList();
    }
    else if($method == "delete"){
        echo SourceCodeMgrService::deleteFile($_POST['file']);
    }
     class SourceCodeMgrService{
         public static function getSourceCodeList(){
            $fileList = SourceCodeService::selectAllFromFileTable();
            return json_encode($fileList);
         }
         public static function deleteFile($filename){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = SourceCodeService::deleteFile($filename);
            if($success){
                if(LocalFileManager::delete("$root/Source Code Files/".$filename.".txt")){
                    return "success";
                }
            }
            return "fail";
         }
     }
?>