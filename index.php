<?php
declare(strict_types = 1);
session_start();  // Start session management

// Check if a user is logged in and set $loggedIn accordingly
$loggedIn = isset($_SESSION['user_id']);

if (!$loggedIn) {
    header('Location: templates/login.html');  // Adjust the path as necessary
    exit;
}

// Database connection setup
$pdo = new PDO('sqlite:database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql_departments = "SELECT * FROM Department";
$stmt = $pdo->prepare($sql_departments);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user details
$userId = $_SESSION['user_id'];
$user = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM User WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Assign profile picture or default
$profilePic = $user['profile_picture'] ?? 'images/icons/default_profile.png';  // Use a default profile picture if none is set
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Finds - Luxury Secondhand Bazaar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Mega:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
    <div class="top-bar">
            
            <span class="logo"><a href="index.php" style="color: inherit; text-decoration: none;">ELITE FINDS</a></span>
            <div class="actions">
                <?php if ($loggedIn): ?>
                    <span class="profile-dropdown">
                        <img id="profile-icon" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" onclick="toggleProfileDropdown();">
                        <div id="dropdown-menu" class="dropdown-content">
                            <a href="templates/user_page.php">User Profile</a>
                            <a href="templates/account_info.php">Account Info</a>
                            <form action="actions/action_logout.php" method="post" class="logout">
                                <button type="submit">Log Out</button>
                            </form>
                        </div>
                    </span>
                <?php else: ?>
                    <span class="profile-dropdown">
                        <img id="profile-icon" src="images/icons/profile.png" alt="Profile" onclick="location.href='templates/login.html';">
                    </span>
                <?php endif; ?>
                <span>
                    <a href="templates/shopping_cart.php">
                        <img src="images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                    </a>
                </span>
            </div>
        </div>
        
        <div class="promo-message">
            <span>MAKE THE MOST OUT OF YOUR LUXURY ITEMS</span>
            <a href="<?php echo $loggedIn ? 'templates/user_page.php' : 'templates/login.html'; ?>">
                <button>START SELLING NOW</button>
            </a>
        </div>
        <nav class="category-bar">
            <ul>
            <?php foreach ($departments as $department): ?>
                    <li>
                        
                        <!-- <a href="templates/<?php //echo strtolower(str_replace(' ', '_', htmlspecialchars($department['d_name']))); ?>_section.php"> !-->
                        <a href="templates/departments.php">
                            <?php echo htmlspecialchars($department['d_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="main-bar">
            <div class="main-text">
                <span>ELITE</span>
                <span>FINDS</span>
                <span>your luxury second hand bazaar</span>
            </div>
            <img src="images/mainpage_logo.png">
        </div>

        <div class="search-bar">
            <form action="templates/search_results.php" method="get">
                <input type="text" name="query" placeholder="Search" required>
            </form>
        </div>

    </header>

    <main>
        <section class="top-picks">
            <h2>Our Top Picks</h2>
            <div class="items">
                <!-- Each item -->
                <div class="item">
                    <img src="images/items/item10_1.png" alt="Balmain Blazer">
                    <p>Balmain Blazer</p>
                    <span>€ 110,00</span>
                    <span>Size M</span>
                </div>
                <div class="item">
                    <img src="images/items/item10_1.png" alt="Balmain Blazer">
                    <p>Balmain Blazer</p>
                    <span>€ 110,00</span>
                    <span>Size M</span>
                </div>
                <div class="item">
                    <img src="images/items/item10_1.png" alt="Balmain Blazer">
                    <p>Balmain Blazer</p>
                    <span>€ 110,00</span>
                    <span>Size M</span>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-section">
            <p>Customer Care</p>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Shipping info</a></li>
                <li><a href="#">Returns policy</a></li>
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
        function toggleProfileDropdown() {
    console.log("toggleProfileDropdown called"); 
    const dropdownContainer = document.querySelector('.profile-dropdown');
    if (dropdownContainer) {
        console.log("dropdownContainer found"); 
        dropdownContainer.classList.toggle('show');
    } else {
        console.log("dropdownContainer not found"); 
    }
}

document.getElementById('profile-icon').addEventListener('click', function (event) {
    event.stopPropagation(); 
    console.log("profile-icon clicked"); 
    toggleProfileDropdown();
});

document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.getElementById('profile-icon');
    const dropdownContainer = document.querySelector('.profile-dropdown');

    profileIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownContainer.classList.toggle('show');
    });

    window.addEventListener('click', function(event) {
        if (!dropdownContainer.contains(event.target) && dropdownContainer.classList.contains('show')) {
            dropdownContainer.classList.remove('show');
        }
    });
});


        
        
     </script>
</body>
</html>
