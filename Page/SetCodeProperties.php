<?php
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once "$root/PHP/CallGraphService.php";
    require_once "$root/PHP/ClassDiagramService.php";
    if(isset($_POST['CUT'])){
        refreshClassSelect($_POST['CUT']);
    }
    function initialDatabase(){
        $conn = Database::connectToDB();
        CallGraphService::initialCallGraphDatabase($conn);
        ClassDiagramService::initialClassDiagramDatabase($conn);
        $conn->close();
    }
    function initialSDSelect(){
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
        echo "<h4>Select Class Diagram</h4>";
        echo "<select id = 'CDSelect'>";
        echo "<option value = '0'selected disabled hidden>Please Select Class Diagram</option>";
        $diagramList = ClassDiagramService::selectAllFromDiagram();
        foreach ($diagramList as $diagram) {
            $diagramID = $diagram['diagramID'];
            $diagramName = $diagram['diagramName'];
            echo "<option value=$diagramID>$diagramName</option>";
        }  
        echo "</select>";
    }
    function initialClassSelect(){
        echo "<h4>Select Class Under Test</h4>";
        echo "<select id = 'ClassSelect'>";
        echo "<option value = '0'selected disabled hidden>Please Select Call Graph</option>";
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