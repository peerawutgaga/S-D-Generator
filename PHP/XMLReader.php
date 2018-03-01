<?php
    class XMLReader{
        public function createCallGraph($target_file){
            $xml = simplexml_load_file($target_file) or die("Error: cannot create object");
        }
        public function processClassDiagram($target_file){
            $xml = simplexml_load_file($target_file) or die("Error: cannot create object");
        }
    }
?>