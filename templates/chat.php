<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$seller_id = $_GET['seller_id'] ?? null;
$product_id = $_GET['product_id'] ?? null;
$user_id = $_SESSION['user_id']; // The ID of the currently logged in user

// Fetch product details
$productStmt = $pdo->prepare("SELECT * FROM Item WHERE id = ?");
$productStmt->execute([$product_id]);
$product = $productStmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Product not found.'); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO messages (from_user_id, to_user_id, product_id, message, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
        $stmt->execute([$user_id, $seller_id, $product_id, $message]);
    }
}

// Fetch existing messages for this product
$stmt = $pdo->prepare("SELECT m.*, u.username FROM messages m JOIN User u ON m.from_user_id = u.id WHERE product_id = ? ORDER BY created_at DESC");
$stmt->execute([$product_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Seller - Elite Finds</title>
    <link rel="stylesheet" href="../css/chat.css"> 
</head>
<body>
    <div class="chat-container">
        <h2>Chat about "<?php echo htmlspecialchars($product['title']); ?>"</h2>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo $message['from_user_id'] === $user_id ? 'sent' : 'received'; ?>">
                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                    <div class="details">
                        <span><?php echo htmlspecialchars($message['username']); ?></span>
                        <span><?php echo $message['created_at']; ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form action="chat.php?seller_id=<?php echo $seller_id; ?>&product_id=<?php echo $product_id; ?>" method="post">
            <textarea name="message" placeholder="Write your message..."></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
