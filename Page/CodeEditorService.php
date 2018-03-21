<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    $method = $_POST['method'];
    if($method =="rename"){
        CodeEditorService::rename($_POST['oldFilename'],$_POST['newFilename']);
    }else if($method == "saveFile"){
        CodeEditorService::saveFile($_POST['filepath'],$_POST['content']);
    }
    class CodeEditorService{
        public static function rename($oldName, $newName){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $fullOldName = $root."/Source Code Files/".$oldName;
            $fullNewName = $root."/Source Code Files/".$newName;
            $success = SourceCodeService::renameFile($oldName, $newName);
            if($success){
                if(rename($fullOldName.".txt",$fullNewName.".txt")){
                    $idx = strrpos($newName,".",-1);
                    echo substr_replace($newName,"-",$idx,1);
                    return;
                }
            }
            echo "failed";
        }
        public static function saveFile($filePath, $content){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $filePath = $root.$filePath;
            $file = fopen($filePath,"w");
            fwrite($file,$content);
            fclose($file);
        }
    }
?>