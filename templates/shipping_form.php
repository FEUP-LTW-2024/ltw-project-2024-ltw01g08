<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/login.php');
    exit;
}

if (!isset($_POST['item_id']) || !isset($_POST['buyer_id']) || !isset($_POST['buyer_name']) || !isset($_POST['buyer_address'])) {
    die("Missing data");
}

$itemId = $_POST['item_id'];
$buyerId = $_POST['buyer_id'];
$buyerName = $_POST['buyer_name'];
$buyerAddress = $_POST['buyer_address'];

try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT title, price FROM Item WHERE id = ?");
$stmt->execute([$itemId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Form - Elite Finds</title>
    <link rel="stylesheet" href="../css/shipping_form.css">
</head>
<body>
    <h1>Shipping Form</h1>
    <div class="shipping-form">
        <p><strong>Item:</strong> <?php echo htmlspecialchars($item['title']); ?></p>
        <p><strong>Price:</strong> €<?php echo number_format($item['price'], 2); ?></p>
        <p><strong>Buyer Name:</strong> <?php echo htmlspecialchars($buyerName); ?></p>
        <p><strong>Buyer Address:</strong> <?php echo htmlspecialchars($buyerAddress); ?></p>
    </div>
</body>
</html>
