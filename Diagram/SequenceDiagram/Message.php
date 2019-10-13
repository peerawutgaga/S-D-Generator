<?php
    namespace SequenceDiagram;
    class Message{
        //TODO Re-Structure
        private $messageID;
        private $messageName;
        private $sentNodeID;
        private $receivedNodeID;
        function __construct($messageID, $messageName){
            $this->messageID = $messageID;
            $this->messageName = $messageName;
        }
        public function getMessageID()
        {
                return $this->messageID;
        }
        public function getMessageName()
        {
                return $this->messageName;
        }
        public function getSentNodeID()
        {
                return $this->sentNodeID;
        }
        public function setSentNodeID($sentNodeID)
        {
                $this->sentNodeID = $sentNodeID;
        } 
        public function getReceivedNodeID()
        {
                return $this->receivedNodeID;
        }
        public function setReceivedNodeID($receivedNodeID)
        {
                $this->receivedNodeID = $receivedNodeID;
        }
    }
?>