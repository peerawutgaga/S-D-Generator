<?php
    class LocalFileManager{
        public static function delete($filepath){
            return unlink($filepath);
        }
        public static function copy($source,$dest){
            return copy($source,$dest);
        }
        public static function download($file){
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
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