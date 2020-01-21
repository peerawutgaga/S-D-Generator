<?php
     $root = realpath($_SERVER["DOCUMENT_ROOT"]);
     require_once "$root/php/database/SourceCodeService.php";
     require_once "$root/php/LocalFileManager.php";
     $method = $_POST['method'];
     if($method == "getList"){
        echo SourceCodeManagerPage::getSourceCodeList();
    }
    else if($method == "delete"){
        echo SourceCodeManagerPage::deleteFile($_POST['file']);
    }else if($method == "rename"){
        echo SourceCodeManagerPage::renameFile($_POST['oldname'],$_POST['newname']);
    }else if($method == "duplicate"){
        echo SourceCodeManagerPage::duplicateFile($_POST['file']);
    }
     class SourceCodeManagerPage{
         public static function getSourceCodeList(){
            $fileList = SourceCodeService::selectAllFromFileTable();
            return json_encode($fileList);
         }
         public static function deleteFile($filename){
             //TODO Rewrit the function
            /*$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $success = SourceCodeService::deleteFile($filename);
            if($success){
                if(LocalFileManager::delete("$root/Source Code Files/".$filename.".txt")){
                    return "success";
                }
            }
            return "fail";*/
         }
         public static function renameFile($oldName, $newName){
             //TODO Rewrite the function
           /* $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $fullOldName = $root."/Source Code Files/".$oldName;
            $fullNewName = $root."/Source Code Files/".$newName;
            $newPath = $root."/Source Code Files/".$newName.".txt";
            if(SourceCodeService::selectFromFileTableByFileName($newName) != null){
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
            echo "failed";*/
        }
        public static function duplicateFile($filename){
            //TODO Rewrite the function
          /*  $originFile = SourceCodeService::selectFromFileTable($filename);
            $idx = strrpos($filename,".",-1);
            $newFilename = substr($filename,0,$idx);
            $extension = substr($filename,$idx,strlen($filename)-1);
            $fileNumber = 1;
            while(SourceCodeService::selectFromFileTable($newFilename." - ".$fileNumber.$extension) != null){
                $fileNumber = $fileNumber + 1;
            }
            $newFilename = $newFilename." - ".$fileNumber.$extension;
            $newLocation = "../Source Code Files/".$newFilename.".txt";
            if(SourceCodeService::insertFile($newFilename,$originFile['fileType'],$originFile['language'],$newLocation)){
                $root = realpath($_SERVER["DOCUMENT_ROOT"]);
                $fullSource = $root."/Source Code Files/".$filename.".txt";
                $fullDest = $root."/Source Code Files/".$newFilename.".txt";
                if(LocalFileManager::copy($fullSource,$fullDest)){
                    echo "success";
                    return;
                }
            }
            echo "fail";*/
        }
     }
?>