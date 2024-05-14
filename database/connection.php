<?php

function getDatabaseConnection() {
    // Define the path to the database file
    $databasePath = __DIR__ . '/database.db'; // __DIR__ gives the directory of the current script

    // Create a new PDO instance and return it
    try {
        $pdo = new PDO('sqlite:' . $databasePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Handle any errors in connection
        exit('Database connection error: ' . $e->getMessage());
    }
}

?>