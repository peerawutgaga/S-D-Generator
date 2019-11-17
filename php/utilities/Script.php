<?php
    class Script{
        public static function alert($message){
            echo "<script type='text/javascript'>
                alert('$message');
            </script>";
        }
        public static function returnTo($pageURL){
            echo "<script type='text/javascript'>
                window.location.href='$pageURL';
            </script>";
        }
        public static function consoleLog( $message ) {
            $message = str_replace("'","\'",$message);
            echo "<script>console.log( '$message' );</script>";
        }
        public static function printObject($object){
            echo "<pre>";
            print_r($object);
            echo "</pre>";
        }
    }
?>