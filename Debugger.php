<?php
     require_once "./Page/CodeEditorService.php";
    $file = CodeEditorService::getSourceCode("./Source Code Files/c.java");
    echo $file;
?>