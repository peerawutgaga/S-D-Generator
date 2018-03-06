<?php
    require_once "../PHP/CallGraphService.php";
    $q = intval($_GET['q']);
    $nodeList = CallGraphService::selectAllFromNode($q,'nodeName');
    echo "<option value = '0'selected disabled hidden>Please Select Call Graph</option>";
    foreach($nodeList as $node){
        echo "<option value = $node>$node</option>";
    }
?>