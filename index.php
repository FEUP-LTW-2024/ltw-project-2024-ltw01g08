<?php
  declare(strict_types = 1);
  require_once(__DIR__ . 'session.php');
  session_start();
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Mega:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Mega:wght@100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    
    <header>
        <div class="top-bar">
            <span class="logo"><a href="../index.html" style="color: inherit; text-decoration: none;">ELITE FINDS</a></span>
            <div class="actions">
                <span>H</span>
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.html">User Profile</a>
                        <a href="../templates/account_info.html">Account Info</a>
                        <form action="actions/action_logout.php" method="post" class="logout">
                            <button type="submit">Log Out</button>
                        </form>
                    </div>
                </span>
                <span>
                    <a href="../templates/shopping_cart.html">
                        <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                    </a>
                </span>
                
            </div>
        </div>
        <div class="promo-message">
            <span>MAKE THE MOST OUT OF YOUR LUXURY ITEMS</span>
            <!-- SE NAO TIVER LOGIN, IR PARA LOGIN PAGE -->
            <a href="../templates/user_page.html">
                <button>START SELLING NOW</button>
            </a>
        </div>
        <nav class="category-bar">
            <ul>
                <li><a href="templates/women_section.html">Women</a></li> 
                <li><a href="templates/men_section.html">Men</a></li> 
                <li><a href="templates/kids_section.html">Kids</a></li> 
                <li><a href="templates/bags_section.html">Bags</a></li> 
                <li><a href="templates/jewelry_section.html">Jewelry</a></li> 
                <li><a href="templates/accessories_section.html">Accessories</a></li> 
            </ul>
        </nav>
        <div class="main-bar">
            <div class="main-text">
                <span>ELITE</span>
                <span>FINDS</span>
                <span>your luxury second hand bazaar</span>
            </div>
            <img src="../images/mainpage_logo.png">
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
        // Function to toggle the dropdown menu
        function toggleProfileDropdown() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
        dropdownContainer.classList.toggle('show');
        }

        // Event listener for clicking the profile icon
        document.getElementById('profile-icon').addEventListener('click', function (event) {
            event.stopPropagation(); 
            toggleProfileDropdown();
        });

        // Event listener for clicking outside the dropdown to close it
        window.addEventListener('click', function () {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
        });
     </script>
</body>
</html>