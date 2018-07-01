<?php
    require_once "SimpleSDProcessor.php";
    require_once "TraditionalSDProcessor.php";
    class SDProcessor{
        private static $conn;
        private static $graphID;
        public static function readSequenceDiagram($fileName, $targetFile){
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                echo "Failed loading XML: "."<br>";
                foreach(libxml_get_errors() as $error) {
                    echo "<br>", $error->message;
                }
                die("XMLProcessor Terminated.");
            }
            self::saveFileToDB($fileName,$targetFile);
            if($xml['Xml_structure'] == 'simple'){
                SimpleSDProcessor::processSimpleSD($xml,self::$conn,self::$graphID);
            }else{
                TraditionalSDProcessor::processTraditionalSD($xml,self::$conn,self::$graphID);
            }
        }
        private static function saveFileToDB($fileName,$targetFile){
            self::$conn = Database::connectToDB();
            // Database::dropDatabase(self::$conn,'callGraph');
            // CallGraphService::initialCallGraphDatabase(self::$conn);
            Database::selectDB(self::$conn,'callGraph');
            CallGraphService::insertToGraphTable(self::$conn, $fileName, $targetFile);
            self::$graphID = CallGraphService::selectFromGraphTable('graphID','graphName',$fileName);
        }
    }
?>