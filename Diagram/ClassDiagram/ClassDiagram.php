<?php
    namespace ClassDiagram;
    class ClassDiagram{
        private $diagramName;
        private $fileTarget;
        private $classes;
        function __construct($diagramName){
            $this->diagramName = $diagramName;
        }
        public function getDiagramName()
        {
                return $this->diagramName;
        }
        
        public function setDiagramName($diagramName)
        {
                $this->diagramName = $diagramName;
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