<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once $root . "/php/database/CallGraphService.php";
require_once $root . "/php/database/ClassDiagramService.php";
require_once $root . "/php/utilities/Script.php";
if (isset($_POST['CUT'])) {
    DiagramSelection::refreshClassSelect($_POST['CUT']);
}

class DiagramSelection
{

    public static function initialSDSelection()
    {
        echo "<h4>Select Call Graph</h4>";
        echo "<select id = 'SDSelect' onchange = selectSD(this.value)>";
        echo "<option value = '0' selected disabled hidden>Please Select Call Graph</option>";
        $graphList = CallGraphService::selectAllFromGraph();
        foreach ($graphList as $graph) {
            $graphID = "sd" . $graph['callGraphId'];
            $graphName = $graph['callGraphName'];
            echo "<option value=$graphID>$graphName</option>";
        }
        echo "</select>";
    }

    public static function initialCDSelection()
    {
        echo "<h4>Select Class Diagram</h4>";
        echo "<select id = 'CDSelect'>";
        echo "<option value = '0' selected disabled hidden>Please Select Class Diagram</option>";
        $diagramList = ClassDiagramService::selectAllFromDiagram();
        foreach ($diagramList as $diagram) {
            $diagramID = "cd" . $diagram['diagramId'];
            $diagramName = $diagram['diagramName'];
            echo "<option value=$diagramID>$diagramName</option>";
        }
        echo "</select>";
    }
}
?>