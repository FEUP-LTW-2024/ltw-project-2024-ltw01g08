<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $sellerId = filter_input(INPUT_POST, 'seller_id', FILTER_VALIDATE_INT);
    $reviewerId = $_SESSION['user_id'];
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

    if (!$itemId || !$sellerId || !$reviewerId || !$rating || !$comment) {
        $_SESSION['error_message'] = "Invalid request.";
        header('Location: ../templates/user_page.php');
        exit;
    }

    try {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            INSERT INTO Review (seller_id, reviewer_id, item_id, rating, comment) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$sellerId, $reviewerId, $itemId, $rating, $comment]);

        $_SESSION['success_message'] = "Review submitted successfully!";
        header('Location: ../templates/user_page.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../templates/user_page.php');
        exit;
    }
} else {
    header('Location: ../templates/user_page.php');
    exit;
}
?>
