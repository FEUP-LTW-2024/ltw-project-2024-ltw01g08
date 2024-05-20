<?php
header('Content-Type: application/json');

$pdo = new PDO('sqlite:../database/database.db'); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;

$sql = "SELECT id, subc_name FROM Subcategory WHERE category_id = :categoryId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

$subcategories = [];

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $subcategories[] = [
            'id' => $row['id'],
            'name' => $row['subc_name']
        ];
    }
    echo json_encode($subcategories);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
