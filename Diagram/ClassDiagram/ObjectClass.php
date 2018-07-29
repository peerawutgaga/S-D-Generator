<?php
    namespace ClassDiagram;
    class ObjectClass{
        const CONCRETE_CLASS = 0;
        const ABSTRACT_CLASS = 1;
        const INTERFACE_CLASS = 2;
        private $className;
        private $classType;
        private $packagePath;
        private $methods;
        function __construct($className){
            $this->className = $className;
            $this->methods = array();
        }
        public function getClassName()
        {
                return $this->className;
        } 
        public function getPackagePath()
        {
                return $this->packagePath;
        }
        public function setPackagePath($packagePath)
        {
                $this->packagePath = $packagePath;
        }
        public function getClassType()
        {
                return $this->classType;
        }
        public function setClassType($classType)
        {
                $this->classType = $classType;
        }
    }
?>