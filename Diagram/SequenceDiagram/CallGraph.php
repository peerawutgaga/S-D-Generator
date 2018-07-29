<?php
        namespace SequenceDiagram;
        class CallGraph{
        private $graphName;
        private $fileTarget;
        private $objectNodes;
        function __construct($graphName){
                $this->graphName = $graphName;
                $this->objectNodes = array();
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