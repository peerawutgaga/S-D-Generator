<?php
    class Script{
        public static function alert($message){
            echo "<script type='text/javascript'>
                alert('$message');
                window.location.href='../index.php';
            </script>";
        }

        public static function consoleLog( $message ) {
            $output = $message;
            if ( is_array( $output ) )
                $output = implode( ',', $output);
            echo "<script>console.log( '" . $output . "' );</script>";
        }
    }
?>