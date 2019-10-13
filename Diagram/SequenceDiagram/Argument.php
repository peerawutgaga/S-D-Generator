<?php
    namespace SequenceDiagram;
    class Argument{
        //TODO Re-Structure
        private $argID;
        private $argName;
        private $argType;
        private $typeModifier;
        function __construct($argID,$argName){
            $this->argID = $argID;
            $this->argName = $argName;
        }
        public function getArgName()
        {
                return $this->argName;
        } 
        public function getArgType()
        {
                return $this->argType;
        } 
        public function getArgID()
        {
                return $this->argID;
        }
        public function setArgType($argType)
        {
                $this->argType = $argType;
        }
 
        public function getTypeModifier()
        {
                return $this->typeModifier;
        }
 
        public function setTypeModifier($typeModifier)
        {
                $this->typeModifier = $typeModifier;
        }
    }
?>