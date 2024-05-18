<?php
// fetch_categories.php
if (isset($_GET['departmentId'])) {
    $departmentId = $_GET['departmentId'];

    try {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT id, c_name FROM Category WHERE department_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$departmentId]);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($categories) {
            foreach ($categories as $category) {
                echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['c_name']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>No Categories Found</option>';
        }
    } catch (PDOException $e) {
        echo '<option value="" disabled>Error fetching categories</option>';
    }
}
?>

