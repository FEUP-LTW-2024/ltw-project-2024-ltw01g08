<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $seller_id = $_SESSION['user_id']; // Example: Ensure you are capturing the seller_id from a session or another source
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $department_id = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_NUMBER_INT);
    $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $subcategory_id = filter_input(INPUT_POST, 'subcategory', FILTER_VALIDATE_INT); // Optional, may be NULL
    $brand = filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_STRING);
    $item_size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
    $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_NUMBER_INT);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("INSERT INTO Item (seller_id, title, item_description, department_id, category_id, subcategory_id, brand, item_size, color, condition, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$seller_id, $title, $description, $department_id, $category_id, $subcategory_id, $brand, $item_size, $color, $condition, $price])) {
        $itemid = $pdo->lastInsertId();

    $image_paths = [];
    $target_dir = "../images/items/";
    for ($i = 1; $i <= 3; $i++) {
        $target_file = $target_dir . "item" . $itemid . "_$i.png";
        if (move_uploaded_file($_FILES["image$i"]["tmp_name"], $target_file)) {
            $image_paths[] = $target_file;
        } else {
            echo "Sorry, there was an error uploading one of your files.";
        }
    }

    // Insert data into the database
    if (count($image_paths) == 3) {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO Item (seller_id, title, item_description, department_id, category_id, subcategory_id, brand, item_size, color, condition, price, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind uploaded image path to the statement
        $image_path = $image_paths[0]; // Store only the first image in the database
        if ($stmt->execute([$seller_id, $title, $description, $department_id, $category_id, $subcategory_id, $brand, $item_size, $color, $condition, $price, $image_path])) {
            header('Location: user_page.php');
            echo "Item added successfully!";
            exit;
        } else {
            echo "Error adding item.";
        }
    }
    }
}

?>
