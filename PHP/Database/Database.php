<?php
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
        public static function connectToDBUsingPDO($db_name){
            $servername = "localhost";
            $username = "root";
            $password = "root";
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                die("Connection failed: " . $e->getMessage());
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
            if ($conn->query($sql) === FALSE) {
                echo "Error at creating database: ".$conn->error."<br>";
            }
        }
        public static function dropDatabase($conn,$db_name){
            $sql = "DROP DATABASE IF EXISTS $db_name";
            if ($conn->query($sql) === FALSE) {
                echo "Error at deleting database: ".$conn->error."<br>";
            }
        }

    }
    
?>