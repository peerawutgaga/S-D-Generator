<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/php/database/CallGraphService.php";
    require_once "$root/php/database/ClassDiagramService.php";
    if(isset($_POST['CUT'])){
        refreshClassSelect($_POST['CUT']);
    }
    function initialSDSelect(){
        //TODO re-design UI
        echo "<h4>Select Call Graph</h4>";
        echo "<select id = 'SDSelect' onchange = selectSD(this.value)>";
        echo "<option value = '0' selected disabled hidden>Please Select Call Graph</option>";
        $graphList = CallGraphService::selectAllFromGraph();
        foreach ($graphList as $graph) {
            $graphID = $graph['graphID'];
            $graphName = $graph['graphName'];
            echo "<option value=$graphID>$graphName</option>";
        }    
        echo "</select>";
    }
    function initialCDSelect(){
        //TODO re-design UI
        echo "<h4>Select Class Diagram</h4>";
        echo "<select id = 'CDSelect'>";
        echo "<option value = '0' selected disabled hidden>Please Select Class Diagram</option>";
        $diagramList = ClassDiagramService::selectAllFromDiagram();
        foreach ($diagramList as $diagram) {
            $diagramID = $diagram['diagramID'];
            $diagramName = $diagram['diagramName'];
            echo "<option value=$diagramID>$diagramName</option>";
        }  
        echo "</select>";
    }
    function initialClassSelect(){
        //TODO re-design UI and function
        echo "<h4>Select Class Under Test</h4>";
        echo "<select id = 'ClassSelect'>";
        echo "<option value = '0' selected disabled hidden>Please Select Class Under Test</option>";
        echo "</select>";
    }
    function refreshClassSelect($cut){
        $nodeList = CallGraphService::selectAllFromNode($cut,'nodeName');
        initialClassSelect();
        foreach($nodeList as $node){
            $nodeID = $node['nodeID'];
            $nodeName = $node['nodeName'];
            echo "<option value = $nodeID>$nodeName</option>";
        }
    }
?>