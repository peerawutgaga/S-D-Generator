<?php
namespace SequenceDiagram;

class ObjectNode
{
    //TODO Re-Structure
    private $nodeID;

    private $nodeName;

    function __construct($nodeID, $nodeName)
    {
        $this->nodeID = $nodeID;
        $this->nodeName = $nodeName;
    }

    public function getNodeName()
    {
        return $this->nodeName;
    }

    public function getNodeID()
    {
        return $this->nodeID;
    }
}
?>