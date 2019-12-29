<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/Page/SourceCodeGen/PHPGenerator.php";
require_once "$root/Page/SourceCodeGen/JavaGenerator.php";
if (isset($_POST['graphId']) && isset($_POST['diagramId']) && isset($_POST['classList']) && isset($_POST['sourceType']) && isset($_POST['sourceLang'])) {
    $graphId = $_POST['graphId'];
    $diagramId = $_POST['diagramId'];
    $classId = $_POST['classList'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::initial($graphId, $diagramId, $classId, $sourceType, $sourceLang);
}

class SourceCodeGenerator
{

    private static $file;

    private static $graphId;

    private static $diagramId;

    private static $classList;

    private static $sourceType;

    private static $sourceLang;

    private static $root;

    public static function initial($graphID, $diagramId, $classList, $sourceType, $sourceLang)
    {
        self::$graphId = $graphID;
        self::$diagramId = $diagramId;
        self::$classList = $classList;
        self::$sourceType = $sourceType;
        self::$sourceLang = $sourceLang;
        self::$root = realpath($_SERVER["DOCUMENT_ROOT"]);
        SourceCodeService::initialSourceCodeDatabase();
        if (! self::checkIfMessagesAreEmpty()) {
            self::createSourceCode();
        }
    }

    private static function checkIfMessagesAreEmpty()
    {
        if (self::$sourceType === "stub") {
            $messageList = CallGraphService::selectMessageBySentNodeID(self::$graphId, self::$classID);
        } else {
            $messageList = CallGraphService::selectMessageByReceivedNodeID(self::$graphId, self::$classID);
        }
        if (empty($messageList)) {
            if (self::$sourceType === "stub") {
                echo "stub error";
            } else {
                echo "driver error";
            }
            return true;
        }
        return false;
    }

    private static function createSourceCode()
    {
        if (self::$sourceType == "stub") {
            $stubList = self::identifyStub();
            $ait = new ArrayIterator($stubList);
            $cit = new CachingIterator($ait);
            $fileList = "";
            if (self::$sourceLang == "Java") {
                foreach ($cit as $stub) {
                    $fileList = $fileList . JavaGenerator::createStub($stub);
                    if ($cit->hasNext()) {
                        $fileList = $fileList . ",";
                    }
                }
            } else {
                // TODO Echo warning when called. This will be disabled.
                /*
                 * foreach($cit as $stub){
                 * $fileList = $fileList.PHPGenerator::createStub($stub);
                 * if($cit->hasNext()){
                 * $fileList = $fileList.",";
                 *
                 * }
                 */
            }
            echo $fileList;
        } else {
            $driver = self::identifyDriver();
            if (self::$sourceLang == "Java") {
                $filename = JavaGenerator::createDriver($driver);
            } else {
                // TODO Echo warning when called. This will be disabled.
                // $filename = PHPGenerator::createDriver($driver);
            }
            echo $filename;
        }
    }

    private static function identifyStub()
    {
        $messageList = CallGraphService::selectMessageBySentNodeID(self::$graphId, self::$classID);
        $stubList = array();
        foreach ($messageList as $message) {
            $node = CallGraphService::selectNodeByNodeID(self::$graphId, $message['receivedNodeID']);
            $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID, $node['nodeName']);
            array_push($stubList, $class);
        }
        $stubList = array_unique($stubList, SORT_REGULAR);
        return $stubList;
    }

    private static function identifyDriver()
    {
        $node = CallGraphService::selectNodeByNodeID(self::$graphId, self::$classID);
        $class = ClassDiagramService::selectClassFromNodeName(self::$diagramID, $node['nodeName']);
        return $class;
    }
}
?>