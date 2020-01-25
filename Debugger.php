<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
//require_once "$root/php/xmlprocessor/sdprocessor/SDProcessor.php";
//require_once "$root/php/xmlprocessor/cdprocessor/CDProcessor.php";
require_once "$root/php/pages/DiagramManagerPage.php";
require_once $root."/php/utilities/Script.php";
require_once $root . '/php/sourcecode/SourceCodeGenerator.php';
SourceCodeGenerator::createCode(8, "99,97", "JAVA");

?>
