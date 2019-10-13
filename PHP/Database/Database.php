<?php
    class Database{
        public static function connectToDB($db_name){
            $servername = "localhost";
            $username = "root";
            $password = "root";
            try {
                if($db_name!=null){
                    $conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
                }else{
                    $conn = new PDO("mysql:host=$servername", $username, $password);
                }
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $e){
                die("Connection failed: " . $e->getMessage()."<br>");
            }
            return $conn;
        }
    }
    
?>