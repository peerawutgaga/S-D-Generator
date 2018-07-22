<?php
    class SequenceDiagram{
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

        public function setGraphName($graphName)
        {
                $this->graphName = $graphName;
        }
  }  
?>