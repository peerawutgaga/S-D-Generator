<?php
require_once "./php/database/CallGraphService.php";
$result = CallGraphService::updateGraphSetCallGraphName(4,"Test");
Script::consoleLog($result);
//Script::printObject($result);
?>
