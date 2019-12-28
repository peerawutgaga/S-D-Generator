<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/php/database/SourceCodeService.php";
    require_once "$root/php/LocalFileManager.php";
    $method = $_POST['method'];
    if($method =="rename"){
        CodeEditorphp::rename($_POST['oldFilename'],$_POST['newFilename']);
    }else if($method == "saveFile"){
        CodeEditorphp::saveFile($_POST['filepath'],$_POST['content']);
    }else if($method == "exportAll"){
        CodeEditorphp::exportAll($_POST['filepath'],$_POST['fileList']);
    }
    class CodeEditorphp{
        public static function rename($oldName, $newName){
            //TODO Rewrite the function
            /*$root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $fullOldName = $root."/Source Code Files/".$oldName;
            $fullNewName = $root."/Source Code Files/".$newName;
            $newPath = "../Source Code Files/".$newName.".txt";
            if(SourceCodeService::selectFromFileTableByFileName($newName)!=null){
                echo "Exist";
                return;
            }
            $success = SourceCodeService::renameFile($oldName, $newName,$newPath);
            if($success){
                if(rename($fullOldName.".txt",$fullNewName.".txt")){
                    $idx = strrpos($fullOldName,".",-1);
                    $fullOldName = substr_replace($fullOldName,"-",$idx,1);
                    $idx = strrpos($newName,".",-1);
                    echo substr_replace($newName,"-",$idx,1);
                    return;
                }
            }
            echo "failed";*/
        }
        public static function saveFile($filepath, $content){
            //TODO Rewrite the function
           /* $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $filepath = $root.$filepath;
            echo $filepath;
            $file = fopen($filepath,"w");
            fwrite($file,$content);
            fclose($file);*/
        }
        public static function exportAll($fileList){
            LocalFileManager::zip($fileList);
        }
    }
?>