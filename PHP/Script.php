<?php
    function alert($message){
        echo "<script type='text/javascript'>
            alert('$message');
            window.location.href='../index.html';
        </script>";
    }

    function consoleLog( $message ) {
        $output = $message;
        if ( is_array( $output ) )
            $output = implode( ',', $output);
        echo "<script>console.log( '" . $output . "' );</script>";
    }
?>