<?php

class Database
{

    private static $conn;

    private static function connectToDB()
    {
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $db_name = "sdgeneratordb";
        try {

            Database::$conn = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);

            Database::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage() . "<br>");
        }
    }

    public static function getConnection()
    {
        if (Database::$conn == null) {
            Database::connectToDB();
        }
        return Database::$conn;
    }
}

?>