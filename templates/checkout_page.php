<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: action_login.php'); // Adjust the path as needed
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id'];
$total = 0;
$shippingCost = 10.00; // Default flat rate shipping cost

try {
    $stmt = $pdo->prepare("SELECT Item.id AS item_id, Item.title, Item.price FROM Cart JOIN Item ON Cart.item_id = Item.id WHERE Cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cart items: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Elite Finds</title>
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
<header>
    </header>
    <main>
    <h1>Checkout</h1>
    <a href="shopping_cart.php" class="back-to-cart-btn">Return to Shopping Cart</a> <!-- Adding return link -->
    <div class="order-summary">
        <h2>Order Summary</h2>
        <?php foreach ($cartItems as $item): ?>
            <p><?php echo htmlspecialchars($item['title']); ?> - € <?php echo number_format($item['price'], 2); ?></p>
            <?php $total += $item['price']; ?>
        <?php endforeach; ?>
    </div>

    <form action="process_order.php" method="post" class="payment-form">
        <fieldset>
            <legend>Address Details</legend>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <label for="district">District:</label>
            <input type="text" id="district" name="district" required>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </fieldset>

        <div class="shipping">
            <p>Shipping: € <?php echo number_format($shippingCost, 2); ?></p>
        </div>

        <fieldset>
            <legend>Payment Details</legend>
            <label for="card-number">Card Number:</label>
            <input type="text" id="card-number" name="card_number" required pattern="\d{16}">
            <label for="exp-date">Expiration Date:</label>
            <input type="text" id="exp-date" name="exp_date" required pattern="\d{2}/\d{2}">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required pattern="\d{3}">
        </fieldset>

        <button type="submit" class="submit-btn">Submit Order</button>
        <p>Total: € <?php echo number_format($total + $shippingCost, 2); ?></p>
    </form>
</main>
    <footer>
        <!-- add -->
    </footer>
</body>
</html>
