<?php
header('Content-Type: application/json');

// Database connection setup
$pdo = new PDO('sqlite:../database/database.db');  // Adjust the path and connection details as necessary
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Getting the department ID from the GET request
$deptId = isset($_GET['deptId']) ? (int)$_GET['deptId'] : 0;

// SQL to fetch categories based on department ID
$sql = "SELECT id, c_name FROM Category WHERE department_id = :deptId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':deptId', $deptId, PDO::PARAM_INT);

// Array to hold the categories
$categories = [];

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = [
            'id' => $row['id'],
            'name' => $row['c_name']
        ];
    }
    // Sending back the result as JSON
    echo json_encode($categories);
} catch (PDOException $e) {
    // Handle potential errors here
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
