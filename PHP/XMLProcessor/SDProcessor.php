<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "SimpleSDProcessor.php";
    require_once "TraditionalSDProcessor.php";
    include_once "$root/Diagram/SequenceDiagram/CallGraph.php";
    use SequenceDiagram\CallGraph;
    class SDProcessor{
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
                
                SimpleSDProcessor::processSimpleSD($xml,self::$graphID);
            }else{
                //TODO Echo warning when sent
               // TraditionalSDProcessor::processTraditionalSD($xml,self::$graphID);
            }
        }
        private static function saveFileToDB($fileName,$targetFile){
            $callGraph = new CallGraph($fileName);
            $callGraph->setFileTarget($targetFile);
            CallGraphService::insertToGraphTable($callGraph);
            self::$graphID = CallGraphService::selectFromGraphByGraphName($fileName)['graphID'];
        }
    }
?>