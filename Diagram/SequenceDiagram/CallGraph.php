<?php
        namespace SequenceDiagram;
        class CallGraph{
        private $graphID;
        private $graphName;
        private $fileTarget;
        private $objectNodes;
        function __construct($graphID, $graphName){
                $this->graphID = $graphID;
                $this->graphName = $graphName;
                $this->objectNodes = array();
        }

        public function getGraphID()
        {
                return $this->graphID;
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
        public function setGraphName($graphName)
        {
                $this->graphName = $graphName;
        }
  }  
?>