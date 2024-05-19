<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/login.php');
    exit;
}

try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'No username';  
$profilePic = $_SESSION['profile_picture'] ?? '../images/icons/avatar.png';

$stmt = $pdo->prepare("SELECT * FROM User WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$isAdmin = false;
$adminStmt = $pdo->prepare("SELECT 1 FROM Admin WHERE user_id = ?");
$adminStmt->execute([$userId]);
if ($adminStmt->fetch()) {
    $isAdmin = true;
}

$favStmt = $pdo->prepare("SELECT Item.*, Favourite.added_at FROM Item JOIN Favourite ON Item.id = Favourite.item_id WHERE Favourite.user_id = ? AND Favourite.is_active = 1");
$favStmt->execute([$userId]);
$favorites = $favStmt->fetchAll(PDO::FETCH_ASSOC);

$soldStmt = $pdo->prepare("
    SELECT Item.*, \"Transaction\".transaction_date, \"Transaction\".buyer_id, User.first_name AS buyer_first_name, User.last_name AS buyer_last_name, User.user_address AS buyer_address
    FROM Item 
    INNER JOIN \"Transaction\" ON Item.id = \"Transaction\".item_id 
    INNER JOIN User ON \"Transaction\".buyer_id = User.id
    WHERE \"Transaction\".seller_id = ?
");
$soldStmt->execute([$userId]);
$soldItems = $soldStmt->fetchAll(PDO::FETCH_ASSOC);

$purchasedStmt = $pdo->prepare("
    SELECT Item.*, \"Transaction\".transaction_date, \"Transaction\".seller_id
    FROM Item 
    INNER JOIN \"Transaction\" ON Item.id = \"Transaction\".item_id 
    WHERE \"Transaction\".buyer_id = ?
");
$purchasedStmt->execute([$userId]);
$purchasedItems = $purchasedStmt->fetchAll(PDO::FETCH_ASSOC);

$reviewsStmt = $pdo->prepare("
    SELECT Review.*, Item.title, Item.image_url
    FROM Review
    INNER JOIN Item ON Review.item_id = Item.id
    WHERE Review.seller_id = ?
");
$reviewsStmt->execute([$userId]);
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Elite Finds</title>
    <link rel="stylesheet" href="../css/user_page.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <form action="search_results.php" method="get">
                <input type="text" name="query" placeholder="Search" class="search-bar" required>
            </form>
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <span class="profile-dropdown">
                    <img id="profile-icon" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="icon">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="user_page.php">User Profile</a>
                        <a href="account_info.php">Account Info</a>
                        <form action="../actions/action_logout.php" method="post" class="logout">
                            <button type="submit">Log Out</button>
                        </form>
                    </div>
                </span>
                <a href="shopping_cart.php">
                    <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart" class="icon cart-icon">
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="user-profile">
            <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="User's Profile" class="profile-photo">
            <div class="user-details">
                <h1><?php echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']); ?>
                <?php if ($isAdmin) { ?>
                    (Admin User)
                <?php } else { ?>
                    (NOT Admin User)
                <?php } ?></h1>
                <p>@<?php echo htmlspecialchars($username); ?></p>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'items')">Items for Sale</button>
            <button class="tab-link" onclick="openTab(event, 'reviews')">Reviews</button>
            <button class="tab-link" onclick="openTab(event, 'favorites')">Favorites</button>
            <button class="tab-link" onclick="openTab(event, 'sold_items')">Sold Items</button>
            <button class="tab-link" onclick="openTab(event, 'purchased_items')">Purchased Items</button>
            <button class="tab-link" onclick="openTab(event, 'add-item')">Add Item</button>
            <?php if ($isAdmin) { ?>
                <button class="tab-link" onclick="openTab(event, 'edit_site')">Add Site Features</button>
            <?php } ?>
        </div>

        <div id="items" class="tab-content">
            <?php include 'items_for_sale_user.php'; ?>
        </div>

        <div id="reviews" class="tab-content">
            <div class="review-container">
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <img src="<?php echo htmlspecialchars($review['image_url']); ?>" alt="<?php echo htmlspecialchars($review['title']); ?>">
                        <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                        <p>Rating: <?php echo htmlspecialchars($review['rating']); ?></p>
                        <p>Comment: <?php echo htmlspecialchars($review['comment']); ?></p>
                        <p>Reviewed on: <?php echo date('j F Y', strtotime($review['timestamp'])); ?></p>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($reviews)): ?>
                    <p>No reviews yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="favorites" class="tab-content">
            <div class="products">
                <?php foreach ($favorites as $item): ?>
                    <div class="product">
                        <a href="product_page.php?product_id=<?php echo $item['id']; ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo htmlspecialchars("../images/items/item{$item['id']}_1.png"); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p>€<?php echo number_format($item['price'], 2); ?></p>
                            <p>Added on: <?php echo date('j F Y', strtotime($item['added_at'])); ?></p>
                        </a>
                        <form onsubmit="removeFavorite(event, <?php echo $item['id']; ?>)">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                        <form onsubmit="addToCart(event, <?php echo $item['id']; ?>)">
                            <button type="submit" class="addcart-btn">Add to cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($favorites)): ?>
                    <p>No favorites yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="sold_items" class="tab-content">
            <div class="products">
                <?php foreach ($soldItems as $item): ?>
                    <div class="product">
                        <a href="product_page.php?product_id=<?php echo $item['id']; ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo htmlspecialchars("../images/items/item{$item['id']}_1.png"); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p>€<?php echo number_format($item['price'], 2); ?></p>
                            <p>Sold on: <?php echo date('j F Y', strtotime($item['transaction_date'])); ?></p>
                        </a>
                        <form action="shipping_form.php" method="post" target="_blank">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="buyer_id" value="<?php echo $item['buyer_id']; ?>">
                            <input type="hidden" name="buyer_name" value="<?php echo htmlspecialchars($item['buyer_first_name'] . ' ' . $item['buyer_last_name']); ?>">
                            <input type="hidden" name="buyer_address" value="<?php echo htmlspecialchars($item['buyer_address']); ?>">
                            <button type="submit" class="shipping-form-btn">Shipping Form</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="purchased_items" class="tab-content" style="display:none;">
            <div class="products">
                <?php foreach ($purchasedItems as $item): ?>
                    <div class="product">
                        <a href="product_page.php?product_id=<?php echo $item['id']; ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo htmlspecialchars("../images/items/item{$item['id']}_1.png"); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p>€<?php echo number_format($item['price'], 2); ?></p>
                            <p>Purchased on: <?php echo date('j F Y', strtotime($item['transaction_date'])); ?></p>
                        </a>
                        <form action="add_review.php" method="post">
                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="seller_id" value="<?php echo $item['seller_id']; ?>">
                            <button type="submit" class="add-review-btn">Add Review</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="add-item" class="tab-content" style="display:none;">
            <?php include 'add_item.php'; ?>
        </div>

        <?php if ($isAdmin) { ?>
            <div id="edit_site" class="tab-content" style="display:none;">
                <?php include 'edit_site.php'; ?>
            </div>
        <?php } ?>
    </main>

    <footer>
        <div class="footer-section">
            <p>&copy;Elite Finds, 2024</p>
        </div>
    </footer>

    <script>
        function openTab(evt, tabName) {
            var tabcontent = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            var tablinks = document.getElementsByClassName("tab-link");
            for (var i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            var tab = document.getElementById(tabName);
            if (tab) {
                tab.style.display = "block";
                evt.currentTarget.className += " active";
            }
        }

        document.getElementById('profile-icon').addEventListener('click', function(event) {
            event.stopPropagation();
            const dropdownContainer = document.querySelector('.profile-dropdown');
            dropdownContainer.classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            openTab(event, 'items'); 
        });

        function removeFavorite(event, itemId) {
            event.preventDefault();  
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_favorite.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status === 200 && this.responseText.trim() === "Success") {
                    var productDiv = document.getElementById('product-' + itemId);
                    if (productDiv) {
                        productDiv.parentNode.removeChild(productDiv);
                    }
                    alert('Favorite removed successfully!');
                } else {
                    alert('Error removing favorite item: ' + this.responseText);
                }
            };
            xhr.send("item_id=" + itemId);
        }

        function addToCart(event, itemId) {
            event.preventDefault(); 
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status == 200) {
                    alert('Item added to cart successfully!');
                } else {
                    alert('Error adding item to cart.');
                }
            };
            xhr.send("item_id=" + itemId + "&user_id=" + <?php echo json_encode($_SESSION['user_id']); ?>);
        }
    </script>
</body>
</html>
