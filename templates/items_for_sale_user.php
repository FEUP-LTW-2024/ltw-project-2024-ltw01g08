<?php
// Start the session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/login.php');
    exit;
}

// Setup PDO connection to the SQLite database
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Retrieve user ID from session
$userId = $_SESSION['user_id'];

// Fetch user's items for sale from the database
$stmt = $pdo->prepare("SELECT * FROM Item WHERE seller_id = ?");
$stmt->execute([$userId]);
$itemsForSale = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="items-sale-container">
    <?php if (!empty($itemsForSale)): ?>
        <?php foreach ($itemsForSale as $item): ?>
            <div class="item">
                <a href="product_page.php?product_id=<?php echo $item['id']; ?>">
                    <img src="<?php echo htmlspecialchars("../images/items/" . $item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" style="width:100px; height:100px;">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                    <p>€<?php echo number_format($item['price'], 2); ?></p>
                    <p>Brand: <?php echo htmlspecialchars($item['brand']); ?></p>
                    <p>Condition: <?php echo htmlspecialchars($item['condition']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no items for sale.</p>
    <?php endif; ?>
</div>
