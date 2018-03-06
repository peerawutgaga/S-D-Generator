<?php
    require_once "./PHP/CallGraphService.php";
    function initialSDSelect(){
        echo "<h4>Select Call Graph</h4>";
        echo "<select id = 'SDSelect'>";
        echo "<option value = '0' selected disabled hidden>Please Select Call Graph</option>";
        $graphList = CallGraphService::selectAllFromGraph('graphName');
        foreach ($graphList as $graphName) {
            echo "<option value=$graphName>$graphName</option>";
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
?>