<?php

    $hostname = "localhost";
    $database = "datatu6_solar";
    $username = "datatu6_solaruser";
    $password = "S2LKDxB7yfDDY8w";

    try {
        $connection = new PDO("mysql:host=$hostname; dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }

?>
