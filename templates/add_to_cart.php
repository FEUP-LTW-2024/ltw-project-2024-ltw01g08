<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // vai para login se não tiver logged in
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (isset($_POST['item_id'], $_SESSION['user_id'])) {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM Cart WHERE user_id = ? AND item_id = ?");
    $stmt->execute([$user_id, $item_id]);
    $item = $stmt->fetch();

    if ($item) {
        $new_amount = $item['amount'] + 1;
        $update_stmt = $pdo->prepare("UPDATE Cart SET amount = ? WHERE user_id = ? AND item_id = ?");
        $update_stmt->execute([$new_amount, $user_id, $item_id]);
    } else {
        $insert_stmt = $pdo->prepare("INSERT INTO Cart (user_id, item_id, amount) VALUES (?, ?, 1)");
        $insert_stmt->execute([$user_id, $item_id]);
    }

    header('Location: shopping_cart.php'); 
    exit;
} else {
    header('Location: product_page.php');
    exit;
}
?>
