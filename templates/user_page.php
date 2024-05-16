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

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM User WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch favorite items
$favStmt = $pdo->prepare("SELECT Item.*, Favourite.added_at FROM Item JOIN Favourite ON Item.id = Favourite.item_id WHERE Favourite.user_id = ? AND Favourite.is_active = 1");
$favStmt->execute([$userId]);
$favorites = $favStmt->fetchAll(PDO::FETCH_ASSOC);

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
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <span class="profile-dropdown">
                    <img id="profile-icon" src="<?php echo $profilePic; ?>" alt="Profile" class="icon">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="user_page.php">User Profile</a>
                        <a href="account_info.php">Account Info</a>
                        <form action="/../actions/action_logout.php" method="post" class="logout">
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
            <img src="<?php echo $profilePic; ?>" alt="User's Profile" class="profile-photo">
            <div class="user-details">
                <h1><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
                <p>@<?php echo $username; ?></p>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'items')">Items for Sale</button>
            <button class="tab-link" onclick="openTab(event, 'reviews')">Reviews</button>
            <button class="tab-link" onclick="openTab(event, 'favorites')">Favorites</button>
            <button class="tab-link" onclick="openTab(event, 'add-item')">Add Item</button>
        </div>

        <div id="items" class="tab-content" style="display:block;">
            <!-- Content for Items for Sale -->
        </div>

        <div id="reviews" class="tab-content" style="display:none;">
            <div class="review-container">
                <!-- Reviews will be dynamically populated here -->
            </div>
        </div>


        <div id="favorites" class="tab-content" style="display:none;">
    <div class="products">
        <?php foreach ($favorites as $item): ?>
            <div class="product">
                <a href="product_page.php?product_id=<?php echo $item['id']; ?>" style="text-decoration: none; color: inherit;">
                    <img src="../images/items/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <h4><?php echo htmlspecialchars($item['title']); ?></h4>
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


       


        <div id="add-item" class="tab-content" style="display: none;">
            <!-- Content for Add Item -->
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
                <li><a href="#">How to sell</a></li>
                <li><a href="#">Terms of service</a></li>
            </ul>
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

            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
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
