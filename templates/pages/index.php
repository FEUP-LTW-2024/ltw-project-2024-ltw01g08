<?php
    declare(strict_types = 1);
    
    require_once(__DIR__ . 'session.php');
    require_once(__DIR__ . 'database/connection.php');
    require_once(__DIR__ . 'templates/tpls/common.php');

    $session = new Session();
    
    $db = dbConnection();

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
<?php
    drawMainPageHeader($session);
?>
<div class="promo-message">
            <span>MAKE THE MOST OUT OF YOUR LUXURY ITEMS</span>
            <a href="<?php echo $loggedIn ? 'templates/user_page.php' : 'templates/htmls/login.html'; ?>">
                <button>START SELLING NOW</button>
            </a>
        </div>
        <nav class="category-bar">
            <ul>
                <li><a href="templates/women_section.php">Women</a></li> 
                <li><a href="templates/men_section.php">Men</a></li> 
                <li><a href="templates/kids_section.php">Kids</a></li> 
                <li><a href="templates/bags_section.php">Bags</a></li> 
                <li><a href="templates/jewelry_section.php">Jewelry</a></li> 
                <li><a href="templates/accessories_section.php">Accessories</a></li> 
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
            <input type="text" placeholder="Search">
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
