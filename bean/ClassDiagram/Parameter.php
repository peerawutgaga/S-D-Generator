<?php
    namespace ClassDiagram;
    class Parameter{
        //TODO Re-Structure
        private $paramID;
        private $paramName;
        private $paramType;
        private $typeModifier;
        function __construct($paramID,$paramName){
            $this->paramID = $paramID;
            $this->paramName = $paramName;
        }
        public function getParamID()
        {
                return $this->paramID;
        } 
        public function getParamName()
        {
                return $this->paramName;
        }
        public function getParamType()
        {
                return $this->paramType;
        }
        public function setParamType($paramType)
        {
                $this->paramType = $paramType;
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