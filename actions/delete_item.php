<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../templates/login.html');
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
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM Item WHERE id = ? AND seller_id = ?");
    $checkStmt->execute([$itemId, $userId]);
    $itemExists = $checkStmt->fetchColumn() > 0;

    if ($itemExists) {
        $stmt = $pdo->prepare("DELETE FROM Item WHERE id = ? AND seller_id = ?");
        $stmt->execute([$itemId, $userId]);

        header('Location: ../templates/user_page.php');
        echo "Success";
        exit;
    } else {
        echo "Failed to delete item.";
    }
} else {
    echo "Invalid request.";
}
?>
