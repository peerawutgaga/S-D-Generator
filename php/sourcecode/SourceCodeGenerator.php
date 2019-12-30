<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . '/sourcecodegen/JavaGenerator.php';
require_once $root . '/php/database/CallGraphService.php';
if (isset($_POST['graphId']) && isset($_POST['diagramId']) && isset($_POST['classList']) && isset($_POST['sourceType']) && isset($_POST['sourceLang'])) {
    $graphId = $_POST['graphId'];
    $diagramId = $_POST['diagramId'];
    $classId = $_POST['classList'];
    $sourceType = $_POST['sourceType'];
    $sourceLang = $_POST['sourceLang'];
    SourceCodeGenerator::initialVariables($graphId, $diagramId, $classId, $sourceType, $sourceLang);
}

class SourceCodeGenerator
{

    private static $file;

    private static $graphId;

    private static $diagramId;

    private static $classList;

    private static $sourceType;

    private static $sourceLang;

    public static function initialVariables($graphId, $diagramId, $classList, $sourceType, $sourceLang)
    {
        self::$graphId = $graphId;
        self::$diagramId = $diagramId;
        self::$classList = $classList;
        self::$sourceType = $sourceType;
        self::$sourceLang = $sourceLang;
    }
    public static function identifyStub($classList){
        
    }
}
?>