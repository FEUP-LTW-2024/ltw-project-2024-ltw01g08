<?php
try {
    // Establish database connection
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if departmentId is set in the GET request
    if (isset($_GET['departmentId'])) {
        $departmentId = $_GET['departmentId'];

        // Prepare the SQL statement to fetch categories based on department ID
        $sql_cat = "SELECT * FROM Category WHERE department_id = :department_id";
        $stmt = $pdo->prepare($sql_cat);
        $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch all categories and return as JSON
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
