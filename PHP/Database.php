<?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    function connectToDB(){
        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        return $conn;
    }
    function selectDB(){
        $db_selected = mysql_select_db('foo', $link);
        if (!$db_selected) {
            die ('Can\'t use foo : ' . mysql_error());
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
    function testCreateDB(){
       
    }
    testCreateDB();
?>