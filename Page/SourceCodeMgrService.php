<?php
     $root = realpath($_SERVER["DOCUMENT_ROOT"]);
     require_once "$root/PHP/SourceCodeService.php";
     if(isset($_POST['getList'])){
            echo SourceCodeMgrService::getSourceCodeList();
    }
     class SourceCodeMgrService{
         public static function getSourceCodeList(){
            $fileList = SourceCodeService::selectAllFromFileTable();
            return json_encode($fileList);
         }
     }
?>