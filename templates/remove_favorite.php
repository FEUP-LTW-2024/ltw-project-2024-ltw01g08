<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['item_id'])) {
    echo "Failed: User not logged in or item ID missing";
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = $_POST['item_id'];

try {
    $pdo = new PDO('sqlite:../database/database.db'); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("DELETE FROM Favourite WHERE user_id = ? AND item_id = ?");
    if ($stmt->execute([$user_id, $item_id]) && $stmt->rowCount() > 0) {
        echo "Success";
    } else {
        echo "Failed: Item not found or could not be deleted";
    }
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}
?>
