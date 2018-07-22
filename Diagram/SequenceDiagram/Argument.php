<?php
    class Argument{
        private $argName;
        private $argType;
        function __construct($argName,$argType){
            $this->argName = $argName;
            $this->argType = $argType;
        }
        public function getArgName()
        {
                return $this->argName;
        } 
        public function getArgType()
        {
                return $this->argType;
        }
    }
?>