<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You must be logged in to add favorites.']);
    exit;
}

// Validate product_id input
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
if (!$product_id) {
    echo json_encode(['error' => 'Invalid product.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the item is already in the user's favorites
    $checkStmt = $pdo->prepare("SELECT id FROM Favourite WHERE user_id = ? AND item_id = ? AND is_active = 1");
    $checkStmt->execute([$userId, $product_id]);
    if ($checkStmt->fetch()) {
        echo json_encode(['message' => 'This item is already in your favorites.']);
    } else {
        // Insert the favorite
        $insertStmt = $pdo->prepare("INSERT INTO Favourite (user_id, item_id) VALUES (?, ?)");
        $insertStmt->execute([$userId, $product_id]);
        echo json_encode(['message' => 'Added to favorites successfully.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => "Database error: " . $e->getMessage()]);
}
?>
