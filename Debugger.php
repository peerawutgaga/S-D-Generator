<?php
     require_once "./Page/CodeEditorService.php";
    $file = CodeEditorService::getSourceCode("./Source Code Files/aaa.java");
    echo $file;
?>