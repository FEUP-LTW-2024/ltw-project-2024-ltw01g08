<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta viewport="width=device-width, initial-scale=1.0">
    <title>Other User's Profile - Elite Finds</title>
    <link rel="stylesheet" href="../css/user_page.css">
</head>
<body>
    <?php
    session_start();

    if (!isset($_GET['user_id'])) {
        header('Location: error_page.php');
        exit;
    }

    $userId = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM User WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('User not found.');
        }

        // Fetch items for sale by the user
        $itemsStmt = $pdo->prepare("SELECT * FROM Item WHERE seller_id = ?");
        $itemsStmt->execute([$userId]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Connection error: " . $e->getMessage());
    }
    ?>
    <header>
        <div class="top-bar">
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <a href="shopping_cart.php">
                    <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart" class="icon cart-icon">
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="user-profile">
            <img src="<?php echo $user['profile_picture'] ?? '../images/icons/avatar.png'; ?>" alt="User's Profile" class="profile-photo">
            <div class="user-details">
                <h1><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
                <p>@<?php echo $user['username']; ?></p>
            </div>
        </div>
        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'items')">Items for sale</button>
            <button class="tab-link" onclick="openTab(event, 'reviews')">Reviews</button>
        </div>
        <div id="items" class="tab-content" style="display:block;">
            <?php if (empty($items)): ?>
                <p>No items for sale.</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <a href="product_page.php?product_id=<?php echo $item['id']; ?>" class="product-link">
                        <div class="product">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p>€<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div id="reviews" class="tab-content" style="display:none;">
            <!-- Reviews fetched and displayed here -->
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
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html>
