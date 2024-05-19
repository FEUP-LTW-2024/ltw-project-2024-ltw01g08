<!-- ainda em desenvolvimento -->

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_id = $_SESSION['user_id']; // Example: Ensure you are capturing the seller_id from a session or another source
    $title = filter_input(INPUT_POST, 'title', 513);
    $description = filter_input(INPUT_POST, 'description', 513);
    $department_id = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_NUMBER_INT);
    $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $subcategory_id = filter_input(INPUT_POST, 'subcategory', FILTER_VALIDATE_INT); // Optional, may be NULL
    $brand = filter_input(INPUT_POST, 'brand', 513);
    $item_size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
    $color = filter_input(INPUT_POST, 'color', 513);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_NUMBER_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image_path = htmlspecialchars(basename($_FILES["image"]["name"]));

    $target_dir = "../images/items/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO Item (seller_id, title, item_description, department_id, category_id, subcategory_id, brand, item_size, color, condition, price, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$seller_id, $title, $description, $department_id, $category_id, $subcategory_id, $brand, $item_size, $color, $condition, $price, $image_path])) {
            echo "Item added successfully!";
        } else {
            echo "Error adding item.";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>
