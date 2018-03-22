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
        public static function zip($filename){
            $root = realpath($_SERVER["DOCUMENT_ROOT"]);
            $root = $root."/Source Code Files/";
            $idx = strrpos($filename,".",-1);
            $zipname = substr($filename,0,$idx);
            $extension = substr($filename,$idx+1);
            $shortZipname = "Source Code Files/".$zipname."-".$extension.".zip";
            $zipname = $root.$zipname."-".$extension.".zip";
            if(file_exists($zipname)){
                self::delete($zipname);
            }
            $zip = new ZipArchive();
            if ($zip->open($zipname, ZipArchive::CREATE)==FALSE) {
                return null;
            }
            $fullfilename = $root.$filename;
            $zip->addFile($fullfilename,$filename);
            $zip->close();
            return $shortZipname;
        }
    }
?>