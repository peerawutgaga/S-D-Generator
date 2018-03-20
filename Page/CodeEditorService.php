<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    $method = $_POST['method'];
    if($method =="rename"){
        CodeEditorService::rename($_POST['oldFilename'],$_POST['newFilename']);
    }else if($method == "getSourceCode"){
        CodeEditorService::getSourceCode($_POST['filepath']);
    }
    class CodeEditorService{
        public static function rename($oldName, $newName){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $fullOldName = $root."/Source Code Files/".$oldName;
            $fullNewName = $root."/Source Code Files/".$newName;
            $success = SourceCodeService::renameFile($oldName, $newName);
            if($success){
                rename($fullOldName,$fullNewName);
                echo "success";
                return;
            }
            echo "failed";
        }
        public static function getSourceCode($filePath){
            $content = show_source($filePath,TRUE);
            echo $content;
        }
    }
?>