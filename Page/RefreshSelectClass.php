<?php
    require_once "../PHP/CallGraphService.php";
    $q = intval($_GET['q']);
    $nodeList = CallGraphService::selectAllFromNode($q,'nodeName');
    foreach($nodeList as $node){
        echo "<option value = $node>$node</option>";
    }
?>