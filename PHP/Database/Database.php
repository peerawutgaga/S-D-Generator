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
                die("Connection failed: " . $e->getMessage());
            }
            return $conn;
        }
        public static function createDatabaseIfNotExist($db_name){
            $conn = self::connectToDB(null);
            try{
                $conn->exec("CREATE DATABASE IF NOT EXISTS $db_name");;
            }catch(PDOException $e){
                die("Create Database Failed: " . $e->getMessage());
            }finally{
                $conn = null;
            }
           
        }
        public static function dropDatabase($db_name){
            $conn = self::connectToDB(null);
            try{
                $conn->exec("DROP DATABASE IF EXISTS $db_name");
            }catch(PDOException $e){
                die("Drop Database Failed: " . $e->getMessage());
            }finally{
                $conn = null;
            }
        }

    }
    
?>