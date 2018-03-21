<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/LocalFileManager.php";
    $method = $_POST['method'];
    if($method =="rename"){
        CodeEditorService::rename($_POST['oldFilename'],$_POST['newFilename']);
    }else if($method == "saveFile"){
        CodeEditorService::saveFile($_POST['filepath'],$_POST['content']);
    }else if($method == "exportFile"){
        CodeEditorService::exportFile($_POST['filepath']);
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
        public static function saveFile($filepath, $content){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $filepath = $root.$filepath;
            echo $filepath;
            $file = fopen($filepath,"w");
            fwrite($file,$content);
            fclose($file);
        }
        public static function exportFile($filepath){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $root = $root."/Source Code Files/";
            if(!LocalFileManager::copy($root.$filepath.".txt",$root.$filepath)){
                echo "export failed";
                return;
            }
            $file = LocalFileManager::zip($filepath);
            if($file != null){
                echo "<a id = \"downloadDiv\" href =\"".$file."\" download>";
                echo "<button id = \"exportBtn\" onclick = \"exportFile()\"><img src = \"Image/export.png\">Export</button>";
                echo "</a>";
            }

        }
    }
?>