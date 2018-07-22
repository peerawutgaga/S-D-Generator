<?php
    namespace ClassDiagram;
    class ClassDiagram{
        private $diagramID;
        private $diagramName;
        private $fileTarget;
        private $classes;
        function __construct($diagramID,$diagramName){
            $this->diagramID = $diagramID;
            $this->diagramName = $diagramName;
        }
        public function getDiagramID()
        {
                return $this->diagramID;
        } 
        public function getDiagramName()
        {
                return $this->diagramName;
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