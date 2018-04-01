<?php
    class LocalFileManager{
        public static function delete($filepath){
            return unlink($filepath);
        }
        public static function copy($source,$dest){
            return copy($source,$dest);
        }
        public static function prepareDownload($file){
            $root = realpath($_SERVER["DOCUMENT_ROOT"])."/Source Code Files/";
            $idx = strrpos($file,"-",-1);
            $file = substr_replace($file,".",$idx,1);
            self::copy($root.$file.".txt",$root.$file);
            return $root.$file;
        }
    }
?>