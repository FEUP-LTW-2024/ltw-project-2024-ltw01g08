<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/login.php');
    exit;
}

try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM Item WHERE id = ? AND seller_id = ?");
    $stmt->execute([$itemId, $userId]);

    if ($stmt->rowCount() > 0) {
        echo "Success";
    } else {
        echo "Failed to delete item.";
    }
} else {
    echo "Invalid request.";
}
?>
