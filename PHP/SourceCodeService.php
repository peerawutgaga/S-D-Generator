<?php
    require_once "Database.php";
    function createSourceCodeTable($conn){
        $createFileTableSQL = "CREATE TABLE IF NOT EXISTS fileTable(
            fileID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            fileName VARCHAR(30) NOT NULL,
            fileType VARCHAR(4) NOT NULL,
            fileLocation VARCHAR(100) NOT NULL,
            createDate TIMESTAMP
        )";
        if ($conn->query($createFileTableSQL) === TRUE) {
            echo "Source code table created successfully";
        } else {
            echo "Error creating source code table: " . $conn->error;
        }
    }
?>
