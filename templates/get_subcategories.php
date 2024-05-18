<?php
header('Content-Type: application/json');

// Database connection setup
$pdo = new PDO('sqlite:../database/database.db');  // Adjust the path and connection details as necessary
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Getting the category ID from the GET request
$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;

// SQL to fetch subcategories based on category ID
$sql = "SELECT id, subc_name FROM Subcategory WHERE category_id = :categoryId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

// Array to hold the subcategories
$subcategories = [];

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $subcategories[] = [
            'id' => $row['id'],
            'name' => $row['subc_name']
        ];
    }
    // Sending back the result as JSON
    echo json_encode($subcategories);
} catch (PDOException $e) {
    // Handle potential errors here
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
