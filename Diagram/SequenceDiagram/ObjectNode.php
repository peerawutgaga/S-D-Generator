<?php
    namespace SequenceDiagram;
    class ObjectNode{
        private $nodeID;
        private $nodeName;
        private $messagesIn;
        private $messagesOut;
        function __construct($nodeID,$nodeName){
            $this->nodeID = $nodeID;
            $this->nodeName = $nodeName;
            $this->messageIn = array();
            $this->messageOut = array();
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