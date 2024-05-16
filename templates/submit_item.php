<!-- ainda em desenvolvimento -->

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $target_dir = "../images/items/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = htmlspecialchars( basename( $_FILES["image"]["name"]));

        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO Item (title, description, price, image_url) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $price, $image_path])) {
            echo "Item added successfully!";
        } else {
            echo "Error adding item.";
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
