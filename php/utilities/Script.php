<?php
    class Script{
        //TODO Revise if it still neeeds or not
        public static function alert($message){
            echo "<script type='text/javascript'>
                alert('$message');
                window.location.href='../index.php';
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