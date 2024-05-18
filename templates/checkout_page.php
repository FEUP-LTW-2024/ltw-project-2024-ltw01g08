<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: action_login.php'); 
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id'];
$total = 0;

$shippingCosts = [
    'Porto' => 3.00,
    'Braga' => 4.00,
    'Viana do Castelo' => 4.00,
    'Aveiro' => 4.50,
    'Vila Real' => 5.00,
    'Bragança' => 5.50,
    'Coimbra' => 5.50,
    'Viseu' => 5.00,
    'Guarda' => 6.00,
    'Leiria' => 6.50,
    'Castelo Branco' => 7.00,
    'Santarém' => 7.50,
    'Portalegre' => 8.00,
    'Lisbon' => 8.50,
    'Setúbal' => 9.00,
    'Évora' => 9.50,
    'Beja' => 10.00,
    'Faro' => 10.00
];

try {
    $stmt = $pdo->prepare("SELECT Item.id AS item_id, Item.title, Item.price FROM Cart JOIN Item ON Cart.item_id = Item.id WHERE Cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cart items: " . $e->getMessage());
}

$shippingCost = 0; 

if (isset($_POST['district'])) {
    $userDistrict = $_POST['district'];
    $shippingCost = $shippingCosts[$userDistrict] ?? 10.00; 
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
    <!--  for header content  -->
</header>
<main>
    <h1>Checkout</h1>
    <a href="shopping_cart.php" class="back-to-cart-btn">Return to Shopping Cart</a>
    <div class="order-summary">
        <h2>Order Summary</h2>
        <?php foreach ($cartItems as $item): ?>
            <p><?php echo htmlspecialchars($item['title']); ?> - €<?php echo number_format($item['price'], 2); ?></p>
            <?php $total += $item['price']; ?>
        <?php endforeach; ?>
        <p>Total: €<?php echo number_format($total, 2); ?></p>
        <form action="" method="post" class="payment-form">
            <fieldset>
                <legend>Address Details</legend>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
                <label for="district">District:</label>
                <select id="district" name="district" required onchange="this.form.submit()">
                    <option value="" disabled selected>Select District</option>
                    <?php foreach ($shippingCosts as $district => $cost): ?>
                        <option value="<?php echo $district; ?>"<?php echo (isset($_POST['district']) && $_POST['district'] === $district) ? ' selected' : ''; ?>>
                            <?php echo $district; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </fieldset>
            <fieldset>
                <legend>Payment Details</legend>
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card_number" required pattern="\d{16}">
                <label for="exp-date">Expiration Date:</label>
                <input type="text" id="exp-date" name="exp_date" required pattern="\d{2}/\d{2}">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required pattern="\d{3}">
            </fieldset>
            <?php if (isset($_POST['district'])): ?>
                <p>Shipping: €<?php echo number_format($shippingCost, 2); ?></p>
                <p>Total with Shipping: €<?php echo number_format($total + $shippingCost, 2); ?></p>
            <?php endif; ?>
            <button type="submit" class="submit-btn">Submit Order</button>
        </form>
    </div>
</main>
<footer>
    <!-- Footer content  -->
</footer>
</body>
</html>
