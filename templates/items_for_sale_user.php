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


<div class="products">
    <?php if (!empty($itemsForSale)): ?>
        <?php foreach ($itemsForSale as $item): 
                        $image_url = "../images/items/item{$item['id']}_1.png";?>
                <a href="product_page.php?product_id=<?php echo $item['id']; ?>">
                    <div class="product">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h4>
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?>">
                        </div>
                        <p>€<?php echo number_format($item['price'], 2); ?></p>
                        <p>Brand: <?php echo htmlspecialchars($item['brand']); ?></p>
                        <p>Condition: <?php echo htmlspecialchars($item['condition']); ?></p>
                        <p>Size <?php echo htmlspecialchars($item['item_size'] ?? 'N/A'); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no items for sale.</p>
    <?php endif; ?>
</div>
