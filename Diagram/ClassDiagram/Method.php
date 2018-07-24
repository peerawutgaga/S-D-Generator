<?php
    namespace ClassDiagram;
    class Method{
        private $methodID;
        private $methodName;
        private $returnType;
        private $returnTypeModifier;
        private $visibility;
        private $isStatic;
        private $isAbstract;
        private $parameters;
        function __construct($methodID,$methodName){
            $this->methodID = $methodID;
            $this->methodName = $methodName;
            $this->parameters = array();
        }
        public function getMethodID()
        {
                return $this->methodID;
        } 
        public function getMethodName()
        {
                return $this->methodName;
        }
        public function getReturnType()
        {
                return $this->returnType;
        }
        public function setReturnType($returnType)
        {
                $this->returnType = $returnType;
        }
        public function getReturnTypeModifier()
        {
                return $this->returnTypeModifier;
        }
        public function setReturnTypeModifier($returnTypeModifier)
        {
                $this->returnTypeModifier = $returnTypeModifier;
        }
        public function getVisibility()
        {
                return $this->visibility;
        }
        public function setVisibility($visibility)
        {
                $this->visibility = $visibility;
        }
        public function getIsStatic()
        {
                return $this->isStatic;
        }
        public function setIsStatic($isStatic)
        {
                $this->isStatic = $isStatic;
        } 
        public function getIsAbstract()
        {
                return $this->isAbstract;
        }
        public function setIsAbstract($isAbstract)
        {
                $this->isAbstract = $isAbstract;
        }
    }
?>