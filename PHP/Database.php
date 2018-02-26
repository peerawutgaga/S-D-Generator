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
    function selectDB($db_name,$conn){
        $db_selected = mysql_select_db($db_name, $conn);
        if (!$db_selected) {
            die ('Cannnot select database: ' . mysql_error());
        }
        return $db_selected;
    }
    function createIfNotExist($db_name,$conn){
        $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
        if ($conn->query($sql) === TRUE) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . $conn->error;
        }
    }
    function createTableIfNotExist($db_name, $table_name,$conn){
        $sql = "CREATE TABLE IF NOT EXISTS $table";
    }
    function saveFileToDB($db_name,$file){
        $conn = connectToDB();
        createIfNotExist($db_name,$conn);

    }


?>