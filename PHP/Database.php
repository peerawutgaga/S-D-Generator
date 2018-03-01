<?php
    require_once "Script.php";
    class Database{
        public static function connectToDB(){
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $conn = new mysqli($servername, $username, $password);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            return $conn;
        }
        public static function selectDB($conn,$db_name){
            $db_selected = mysqli_select_db($conn, $db_name);
            if (!$db_selected) {
                die ('Cannot select database : ' . mysql_error());
            }
        }
        public static function createDatabaseIfNotExist($conn, $db_name){
            $sql = "CREATE DATABASE IF NOT EXISTS $db_name";
            if ($conn->query($sql) === TRUE) {
                Script::consoleLog("Database created successfully");
            } else {
                Script::consoleLog("Error creating database: " . $conn->error);
            }
        }
    }
    
?>