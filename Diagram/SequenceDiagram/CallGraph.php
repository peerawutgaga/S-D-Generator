<?php
namespace SequenceDiagram;

class CallGraph
{

    // TODO Re-Structure
    private $graphName;

    private $fileTarget;

    function __construct($graphName)
    {
        $this->graphName = $graphName;
    }

    public function setGraphName($graphName)
    {
        $this->graphName = $graphName;
    }

    public function getGraphName()
    {
        return $this->graphName;
    }

    public function getFileTarget()
    {
        return $this->fileTarget;
    }

    public function setFileTarget($fileTarget)
    {
        $this->fileTarget = $fileTarget;
    }
}
?>