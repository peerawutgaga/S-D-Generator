<?php
require_once "./php/database/ClassDiagramService.php";
$result = ClassDiagramService::deleteFromDiagramByDiagramId(2);
//Script::consoleLog($result);
Script::printObject($result);
?>
