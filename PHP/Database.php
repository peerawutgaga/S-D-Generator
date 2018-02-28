<?php
    function connectToDB(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $conn = new mysqli($servername, $username, $password);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        return $conn;
    }
    function selectDB($conn,$db_name){
        $db_selected = mysqli_select_db($conn, $db_name);
        if (!$db_selected) {
            die ('Cannot select database : ' . mysql_error());
        }
    }
    function createDatabaseIfNotExist($conn, $db_name){
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            consoleLog("Database created successfully");
        } else {
            consoleLog("Error creating database: " . $conn->error);
        }
    }
    function consoleLog( $message ) {
        $output = $message;
        if ( is_array( $output ) )
            $output = implode( ',', $output);
    
        echo "<script>console.log( '" . $output . "' );</script>";
    }
?>