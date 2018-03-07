<?php
    require_once "./PHP/CallGraphService.php";
    function initialDatabase(){
        $conn = Database::connectToDB();
        CallGraphService::initialCallGraphDatabase($conn);
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
        $dircontents = scandir('./Class Diagrams/');
        foreach ($dircontents as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ($extension == 'xml') {
                echo "<option value=$file>$file</option>";
            }
        }   
        echo "</select>";
    }
    function initialClassSelect(){
        echo "<h4>Select Class Under Test</h4>";
        echo "<select id = 'ClassSelect'>";
        echo "<option value = '0'selected disabled hidden>Please Select Call Graph</option>";
        echo "</select>";
    }
?>