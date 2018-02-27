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
        return $db_selected;
    }
    function createDatabaseIfNotExist($conn, $db_name){
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }
    } 
    
?>