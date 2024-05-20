<?php
    session_start();

    if (!isset($_GET['user_id'])) {
        header('Location: login.html');
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


    $itemsStmt = $pdo->prepare("
        SELECT * FROM Item 
        WHERE seller_id = ? 
        AND id NOT IN (SELECT item_id FROM \"Transaction\" WHERE seller_id = ?)
    ");
        $itemsStmt->execute([$userId]);
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Connection error: " . $e->getMessage());
    }
    ?>
    
            
            
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta viewport="width=device-width, initial-scale=1.0">
    <title>User's Profile - Elite Finds</title>
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
                <p>@<?php echo $user['username']; ?></p>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'items')">Items for sale</button>
            <button class="tab-link" onclick="openTab(event, 'reviews')">Reviews</button>
        </div>

        <div id="items">
            <?php if (empty($items)): ?>
                <p>No items for sale.</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="product-link">
                        <div class="product">
                            <h3><?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?></h3>
                            <div class="image-container">
                                <img src="<?php echo htmlspecialchars("../images/items/item{$item['id']}_1.png"); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?>">
                            </div>
                            <p>€<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                            <p>Size <?php echo htmlspecialchars($item['item_size'] ?? 'N/A'); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="reviews" class="tab-content">
        <div class="review-container">
                <!-- Reviews vêm para aqui -->
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-section">
            <p>&copy;Elite Finds, 2024</p>
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
    </script>
</body>
</html>
