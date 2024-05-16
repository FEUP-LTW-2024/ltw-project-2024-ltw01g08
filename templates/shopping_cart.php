<?php
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/action_login.php'); 
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id']; 
try {
    $stmt = $pdo->prepare("SELECT Item.id AS item_id, Item.title, Item.image_url, Item.item_size, Item.price, Item.seller_id FROM Cart JOIN Item ON Cart.item_id = Item.id WHERE Cart.user_id = ?");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cart items: " . $e->getMessage());
}
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Elite Finds</title>
    <link rel="stylesheet" href="../css/shopping_cart.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php" style="color: inherit; text-decoration: none;">ELITE FINDS</a></span>
            <div class="actions">
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile" class="icon">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.php">User Profile</a>
                        <a href="../templates/account_info.php">Account Info</a>
                    </div>
                </span>                
                <span><img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart"></span>
            </div>
        </div>
    </header>

    <main>
    <h1>Shopping cart</h1>
    <?php foreach ($cartItems as $item): ?>
        <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['item_id']); ?>" class="product-link">
            <div class="cart-item">
                <img src="../images/items/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="item-details">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p><?php echo htmlspecialchars($item['item_size']); ?></p>
                    <p>Seller: <?php 
                        $seller_username_stmt = $pdo->prepare("SELECT username FROM User WHERE id = ?");
                        $seller_username_stmt->execute([$item['seller_id']]);
                        $seller_username = $seller_username_stmt->fetchColumn();
                        echo htmlspecialchars($seller_username ? '@' . $seller_username : 'Unknown');
                    ?></p>
                    <span class="item-price">€ <?php echo number_format($item['price'], 2); ?></span>
                </div>
                <a href="remove_from_cart.php?item_id=<?php echo $item['item_id']; ?>" class="remove-item">🗑 Remove</a>
            </div>
        </a>
        <?php 
        $total += $item['price'];
    endforeach; ?>

        <div class="cart-summary">
            <div class="line"></div> 
            <p class="subtotal">SUBTOTAL: <span>€ <?php echo number_format($total, 2); ?></span></p>
            <p class="shipping">SHIPPING: <span>€ 10,00</span></p>
            <p class="total">TOTAL: <span>€ <?php echo number_format($total + 10, 2); ?></span></p>
            <button class="checkout-btn">CHECKOUT</button>
        </div>
    </main>
    <footer>
        <div class="footer-section">
            <p>Customer Care</p>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Refer a friend</a></li>
                <li><a href="#">Shipping info</a></li>
                <li><a href="#">Returns policy</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <p>Company</p>
            <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#">How to sell</a></li>
                <li><a href="#">Terms of service</a></li>
            </ul>
        </div>
    </footer>

    <script>
        function toggleProfileDropdown() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            dropdownContainer.classList.toggle('show');
        }

        document.getElementById('profile-icon').addEventListener('click', function (event) {
            event.stopPropagation(); 
            toggleProfileDropdown();
        });

        window.addEventListener('click', function () {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
        });
    </script>
</body>
</html>
