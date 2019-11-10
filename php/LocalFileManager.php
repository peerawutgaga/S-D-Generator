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
        public static function zip($fileList){
            $root = realpath($_SERVER["DOCUMENT_ROOT"])."/Source Code Files/";
            if(!is_dir($root.'/tmp')){
                mkdir($root.'/tmp');
            }
            $zip = new ZipArchive;
            $filename = "$root/tmp/Source_Code_Files.zip";
            if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
                echo "failed";
                return;
            }
            $fileList = explode(",",$fileList);
            foreach($fileList as $file){
                $source = $root.$file.".txt";
                $desc = $root."/tmp/".$file;
                self::copy($source,$desc);
                $zip->addFile($desc,$file);
            }
            $zip->close();
            self::copy($filename,$root."Source_Code_Files.zip");
            //clear tmp file
            foreach($fileList as $file){
                $desc = $root."/tmp/".$file;
                self::delete($desc);
            }
            self::delete($filename);
            rmdir($root.'/tmp');
        }
    }
?>