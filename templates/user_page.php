<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../php/login.php');
    exit;
}

// Extract user details from session
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$profilePic = $_SESSION['profile_picture'];  

// Database connection
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $stmt = $pdo->prepare("SELECT * FROM User WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database :" . $e->getMessage());
}
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
            <span class="logo"><a href="../index.html">ELITE FINDS</a></span>
            <div class="actions">
                <span class="profile-dropdown">
                    <img id="profile-icon" src="<?php echo $profilePic; ?>" alt="Profile" class="icon">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="user_page.php">User Profile</a>
                        <a href="account_info.php">Account Info</a>
                    </div>
                </span>
                <a href="shopping_cart.html">
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
                <!-- Placeholder for additional user data -->
            </div>
        </div>
        <div class="tabs">
            <button class="tab-link active" onclick="openTab(event, 'items')">Items for sale</button>
            <button class="tab-link" onclick="openTab(event, 'reviews')">Reviews</button>
            <button class="tab-link" onclick="openTab(event, 'favorites')">Favorites</button>
            <button class="add-item-button" onclick="openAddItemPage()">Add Item</button>
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
        function openAddItemPage() {
            window.location.href = "../templates/add_item.html"; 
        }

        let currentPage = {
            items: 1,
            favorites: 1
        };
        const itemsPerPage = 6;
        const products = [
        { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
        ];

        const reviews = [
            { date: "25/01/2024", rating: 5, user: "marysmith", comment: "Very nice!" },
            { date: "01/10/2023", rating: 4, user: "marysmith", comment: "Okay." },
            { date: "01/10/2023", rating: 3, user: "marysmith", comment: "Could be better." },
        ];

        const favoriteProducts = [
        { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
            { img: "../images/items/item1_1.png", title: "Vintage Gucci Dress", price: "€ 60,00", size: "Size EU 36" },
        ];

        function displayProducts(category) {
            const page = currentPage[category];
            const data = (category === 'favorites' ? favoriteProducts : products);
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const productsToShow = data.slice(start, end);

            const container = document.querySelector(`#${category} .products`);
            container.innerHTML = productsToShow.map(product => `
                <div class="product">
                    <img src="${product.img}" alt="${product.title}">
                    <p>${product.title} ${product.price}</p>
                    <p>${product.size}</p>
                </div>
            `).join('');

            document.getElementById(`${category}-page-number`).textContent = page;
        }

        function changePage(category, step) {
            const numberOfPages = Math.ceil((category === 'reviews' ? reviews.length : (category === 'favorites' ? favoriteProducts.length : products.length)) / itemsPerPage);
            currentPage[category] += step;
            currentPage[category] = Math.max(1, Math.min(numberOfPages, currentPage[category]));
    
            if (category === 'reviews') {
                displayReviews();
            } else {
                displayProducts(category);
            }
        }

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
            if (!currentPage[tabName]) { 
                currentPage[tabName] = 1; 
            }
            displayProducts(tabName); 
        }

        function displayReviews() {
            const reviewContainer = document.querySelector('#reviews .review-container');
            if (reviews.length === 0) {
                reviewContainer.innerHTML = '<p>No reviews yet.</p>';
                return;
            }

            reviewContainer.innerHTML = reviews.map(review => `
                <div class="review">
                    <p class="review-date">${review.date}</p>
                    <p class="review-rating">Rating: ${"★".repeat(review.rating)}${"☆".repeat(5 - review.rating)}</p>
                    <p class="review-user">${review.user}</p>
                    <p class="review-comment">${review.comment}</p>
                </div>
            `).join('');
        }

        document.addEventListener('DOMContentLoaded', () => {
            displayProducts('items'); 
            displayProducts('favorites'); 
            displayReviews();        
        });

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

