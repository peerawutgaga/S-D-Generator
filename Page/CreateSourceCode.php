<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/SourceCodeService.php";
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    $graphID = $_POST['graphID'];
    $diagramID = $_POST['diagramID'];
    $classID = $_POST['CUT'];
    $filename = $_POST['filename'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang);
    class SourceCodeGenerator{
        private static $file;
        private static $graphID;
        private static $diagramID;
        private static $classID;
        private static $filename;
        private static $sourceType;
        private static $sourceLang;
        private static $root;
        public static function createSourceCode($graphID, $diagramID, $classID, $filename, $sourceType, $sourceLang){
           self::$graphID = $graphID;
           self::$diagramID = $diagramID;
           self::$classID = $classID;
           self::$filename = $filename;
           self::$sourceType = $sourceType;
           self::$sourceLang = $sourceLang;
           self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
           SourceCodeService::initialSourceCodeDatabase();
           self::createFile();
        }
        private static function createFile(){
            //echo $graphID." ".$diagramID." ".$classID." ".$filename." ".$sourceType." ".$sourceLang;
            if(self::$sourceLang == 'Java'){
                $filePath = self::$root."/Source Code Files/".self::$filename.".java";
                self::$file = fopen($filePath,'w');
            }else{
                $filePath = self::$root."/Source Code Files/".self::$filename.".php";
                self::$file = fopen($filePath,'w');
            }
            SourceCodeService::insertFile(self::$filename, self::$sourceType, self::$sourceLang, $filePath);
        }
    }
    
    
?>