<?php

function getDatabaseConnection() {
    $databasePath = __DIR__ . '/database.db'; 

    try {
        $pdo = new PDO('sqlite:' . $databasePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        exit('Database connection error: ' . $e->getMessage());
    }
}

?>