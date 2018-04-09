<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    class JavaGenerator{
        private static function getDefaultValue($returnType){
            switch($returnType){
                case "float" : return "0.0";
                case "int" : return "0";
                case "double" : return "0.0";
                case "char" : return "'\\u0000'";
                case "boolean" : return "false";
                case "long" : return "0";
                case "short" : return "0";
                case "byte" : return "0";
                default : return "null";
            }
        }
    }
?>