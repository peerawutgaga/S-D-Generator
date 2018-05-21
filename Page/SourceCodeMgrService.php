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
    }else if($method == "rename"){
        echo SourceCodeMgrService::renameFile($_POST['oldname'],$_POST['newname']);
    }else if($method == "export"){
        echo SourceCodeMgrService::exportFile($_POST['file']);
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
         public static function renameFile($oldName, $newName){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $fullOldName = $root."/Source Code Files/".$oldName;
            $fullNewName = $root."/Source Code Files/".$newName;
            $newPath = "../Source Code Files/".$newName.".txt";
            if(SourceCodeService::selectFromFileTable($newName)!=null){
                echo "Exist";
                return;
            }
            $success = SourceCodeService::renameFile($oldName, $newName,$newPath);
            if($success){
                if(rename($fullOldName.".txt",$fullNewName.".txt")){
                    echo "success";
                    return;
                }
            }
            echo "failed";
        }
        public static function exportFile($filename){
            
        }
     }
?>