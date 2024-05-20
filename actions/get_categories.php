<?php
header('Content-Type: application/json');

$pdo = new PDO('sqlite:../database/database.db');  
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$deptId = isset($_GET['deptId']) ? (int)$_GET['deptId'] : 0;

$sql = "SELECT id, c_name FROM Category WHERE department_id = :deptId";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':deptId', $deptId, PDO::PARAM_INT);

$categories = [];

try {
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $categories[] = [
            'id' => $row['id'],
            'name' => $row['c_name']
        ];
    }
    echo json_encode($categories);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
