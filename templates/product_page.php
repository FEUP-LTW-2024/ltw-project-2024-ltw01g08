<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page - Elite Finds</title>
    <link rel="stylesheet" href="../css/product_page.css">
</head>
<body>
    <?php
    session_start();

    $loggedIn = isset($_SESSION['user_id']);  

    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['product_id'])) {
        header('Location: error_page.php');
        exit;
    }
    
    $product_id = filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $stmt = $pdo->prepare("SELECT Item.*, User.username as seller_username FROM Item JOIN User ON Item.seller_id = User.id WHERE Item.id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        die('Product not found.');
    }
    ?>
    <header>
        <div class="top-bar">
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <?php if ($loggedIn): ?>
                            <a href="../templates/user_page.php">User Profile</a>
                            <a href="../templates/account_info.php">Account Info</a>
                        <?php else: ?>
                            <a href="../templates/login.php">Log In</a>
                            <a href="../templates/register.php">Register</a>
                        <?php endif; ?>
                    </div>
                </span>
                <span>
                    <a href="../templates/shopping_cart.php">
                        <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                    </a>
                </span>
            </div>
        </div>
    </header>

    <main>
        <nav class="category-bar">
            <ul>
                <li class="pink-highlight"><a href="women_section.php">Women</a></li> 
                <li><a href="men_section.php">Men</a></li> 
                <li><a href="kids_section.php">Kids</a></li> 
                <li><a href="bags_section.php">Bags</a></li> 
                <li><a href="jewelry_section.php">Jewelry</a></li> 
                <li><a href="accessories_section.php">Accessories</a></li> 
            </ul>
        </nav> 

        <div class="product-container">
            <div class="product-images">
                <img src="<?php echo htmlspecialchars("../images/items/item{$product['id']}_1.png"); ?>" alt="Main Image" onclick="openImageModal(this, 0)">

                <div class="additional-images">
                    <img src="<?php echo htmlspecialchars("../images/items/item{$product['id']}_2.png"); ?>" alt="Other Image 1" onclick="openImageModal(this, 1)">
                    <img src="<?php echo htmlspecialchars("../images/items/item{$product['id']}_3.png"); ?>" alt="Other Image 2" onclick="openImageModal(this, 2)">
                </div>

            </div>

<div class="product-details">
    <h2>€ <?php echo number_format($product['price'], 2); ?></h2>
    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
    <ul>
        <li><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></li>
        <li><strong>Size:</strong> <?php echo htmlspecialchars($product['item_size']); ?></li>
        <li><strong>Color:</strong> <?php echo htmlspecialchars($product['color']); ?></li>
        <li><strong>Condition:</strong> <?php echo htmlspecialchars($product['condition']); ?></li>
    </ul>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($product['item_description']); ?></p>

    <!-- Add to Cart Form -->
    <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="item_id" value="<?php echo $product['id']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <button type="submit" class="add-to-cart">Add to cart</button>
    </form>

    <button class="add-to-favorites">Add to favourites</button>
    <button class="make-offer" onclick="window.location.href='chat.php?seller_id=<?php echo $product['seller_id']; ?>&product_id=<?php echo $product['id']; ?>'">Make an offer</button>
    <div class="seller-info">
        <p><strong>Seller:</strong> <a href="other_users_page.php?user_id=<?php echo $product['seller_id']; ?>"><?php echo htmlspecialchars($product['seller_username']); ?></a></p>
    </div>
</div>

        </div>
    </main>

    <footer>
        <div class="footer-section">
            <p>Customer Care</p>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Shipping info</a></li>
                <li><a href="#">Returns policy</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <p>Company</p>
            <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#">Terms of service</a></li>
            </ul>
        </div>
    </footer>

    <script>
        function openImageModal(imageSrc) {
            const modal = document.createElement('div');
            modal.classList.add('modal');
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <img src="${imageSrc}" alt="Large Image">
                </div>
            `;
            document.body.appendChild(modal);

            modal.querySelector('.close-modal').addEventListener('click', function() {
                document.body.removeChild(modal);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
        const additionalImages = document.querySelectorAll('.additional-images img');

        additionalImages.forEach(function(image, index) {
            image.addEventListener('click', function() {
                const imageSrc = image.getAttribute('src');
                openImageModal(imageSrc);
            });
        });
    })
</script>

</body>
</html>
