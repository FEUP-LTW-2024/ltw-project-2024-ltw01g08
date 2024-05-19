<?php
    declare(strict_types = 1);
    require_once(__DIR__ . '/../session.php');
    $session = new Session();
?>

<?php function drawHeader(Session $session) { ?>
    <header>
        <div class="top-bar">
            <form action="search_results.php" method="get">
                <input type="text" name="query" placeholder="Search" class="search-bar" required>
            </form>
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <a href="all_chats.php">
                    <span>M</span>
                </a>
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.php">User Profile</a>
                        <a href="../templates/account_info.html">Account Info</a>
                    </div>
                </span>
                <span>
                    <a href="shopping_cart.php">
                        <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                    </a>
                </span>
            </div>
        </div>
    </header>
<?php } ?>


<?php function drawMainPageHeader(Session $session) { ?>
    <header>
    <div class="top-bar">
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
        <?php 
            if ($session->isLoggedIn()) {
                drawLoggedIn($session);
            }
            else {
                drawNotLoggedIn();
            }
        ?>
    </nav>
<?php } ?>



<?php function drawLoggedIn(Session $session) { ?>
    <div class="actions">
            <a href="all_chats.php">
                <span>M</span>
            </a>
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.php">User Profile</a>
                        <a href="../templates/account_info.html">Account Info</a>
                        <form action="actions/action_logout.php" method="post" class="logout">
                            <button type="submit">Log Out</button>
                        </form>
                    </div>
                </span>
                <span>
                    <a href="shopping_cart.php">
                        <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                    </a>
                </span> 
            </div>
        </div>
    </header>
    <?php 
        drawLogoutform($session);
} ?>



<?php function drawNotLoggedIn() { ?>
    <div class="actions">
                
            <a href="../actions/login_action.php">Login</a>
            <a href="../php/signup.php">Sign up</a>
            <span>
                <a href="shopping_cart.php">
                    <img src="../images/icons/shopping_cart_icon.png" alt="Shopping Cart">
                </a>
            </span> 
        </div>
    </header>
<?php } ?>


<?php function drawFooter() { ?>
    <footer>
    <div class="footer-content">
            <div>
                <h4>Customer Care</h4>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Refer a friend</a></li>
                    <li><a href="#">Shipping info</a></li>
                    <li><a href="#">Returns policy</a></li>
                    <li><a href="#">Contact us</a></li>
                </ul>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">How to sell</a></li>
                    <li><a href="#">Terms of service</a></li>
                </ul>
            </div>
        </div>
    </footer>
        </div>
    </body>
  </html> 
<?php } ?>


