<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sort = filter_input(INPUT_GET, 'sort', 513);
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";

    $categories = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $conditions = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $size = filter_input(INPUT_GET, 'size', 513);

    // Base SQL
    $sql = "SELECT * FROM Item WHERE department_id = 122";
    $params = [];

    // Handle multiple categories
    if (!empty($categories) && !in_array('all', $categories)) {
        $categoryPlaceholders = implode(', ', array_fill(0, count($categories), '?'));
        $sql .= " AND category_id IN (SELECT id FROM Category WHERE c_name IN ($categoryPlaceholders) AND department_id = 122)";
        $params = array_merge($params, $categories);
    }

    // Handle multiple conditions
    if (!empty($conditions)) {
        $conditionPlaceholders = implode(', ', array_fill(0, count($conditions), '?'));
        $sql .= " AND condition IN ($conditionPlaceholders)";
        $params = array_merge($params, $conditions);
    }

    // Handle size
    if (!empty($size)) {
        $sql .= " AND item_size = ?";
        $params[] = $size;
    }

    // Sorting
    $sql .= " ORDER BY price $order";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Section - Elite Finds</title>
    <link rel="stylesheet" href="../css/department_sections.css">
</head>
<body>
    <header>
    <div class="top-bar">
            <input type="text" placeholder="Search" class="search-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
            <div class="actions">
                <span>H</span>
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

    <main>
    <nav class="category-bar">
            <ul>
                <li class="pink-highlight"><a href="women_section.php">Women</a></li> 
                <li><a href="men_section.html">Men</a></li> 
                <li><a href="kids_section.html">Kids</a></li> 
                <li><a href="bags_section.html">Bags</a></li> 
                <li><a href="jewelry_section.html">Jewelry</a></li> 
                <li><a href="accessories_section.html">Accessories</a></li> 
            </ul>
        </nav> 


        <aside class="sorter-sidebar">
            <h2>Sort By</h2>
            <form id="sorters">
                <label for="sort-price">Price:</label>
                <select id="sort-price" onchange="sortProducts();">
                    <option value="default">--</option>
                    <option value="low-to-high">Low to High</option>
                    <option value="high-to-low">High to Low</option>
                </select>
            </form>
        </aside>
        
        <aside class="filter-sidebar">
            <h2>Filter By</h2>
            <form id="filters" method="GET">
                <fieldset>
                    <legend>Category</legend>
                    <label><input type="checkbox" value="all" name="category" checked>All</label>
                    <label><input type="checkbox" value="dresses" name="category">Dresses</label>
                    <label><input type="checkbox" value="coats" name="category">Coats</label>
                    <label><input type="checkbox" value="shoes" name="category">Shoes</label>
                    <label><input type="checkbox" value="jeans" name="category">Jeans</label>
                    <label><input type="checkbox" value="pants" name="category">Pants</label>
                    <label><input type="checkbox" value="shorts" name="category">Shorts</label>
                    <label><input type="checkbox" value="skirts" name="category">Skirts</label>
                    <label><input type="checkbox" value="swimwear" name="category">Swimwear</label>
                    <label><input type="checkbox" value="tops" name="category">Tops</label>
                </fieldset>
            
    
                <fieldset>
                    <legend>Subcategory</legend>
                    <div id="subcategory-container">
                        <!-- Subcategories will be populated here -->
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Condition</legend>
                    <div id="condition-container">
                        <label><input type="checkbox" name="condition" value="Excellent">Excellent</label>
                        <label><input type="checkbox" name="condition" value="Very good">Very good</label>
                        <label><input type="checkbox" name="condition" value="Good">Good</label>
                        <label><input type="checkbox" name="condition" value="Bad">Bad</label>
                    </div>
                </fieldset>
        
                <fieldset>
                    <legend>Size</legend>
                    <div id="size-container">
                        <label><input type="checkbox" name="size" value="XS">XS</label>
                        <label><input type="checkbox" name="size" value="S">S</label>
                        <label><input type="checkbox" name="size" value="M">M</label>
                        <label><input type="checkbox" name="size" value="L">L</label>
                        <label><input type="checkbox" name="size" value="XL">XL</label>
                    </div>
                </fieldset>
        
    
                <label for="price">Price</label>
                <input type="text" id="price" placeholder="Min Price">
                <input type="text" id="price" placeholder="Max Price">

                <button type="submit">Apply Filters</button>
            </form>
           

        </aside>

        <div class="products">
    <?php foreach ($items as $item): ?>
        <div class="product">
    <?php
        $seller_username_query = "SELECT username FROM User WHERE id = :seller_id";
        $seller_username_stmt = $pdo->prepare($seller_username_query);
        $seller_username_stmt->execute(array(':seller_id' => $item['seller_id']));
        $seller_username = $seller_username_stmt->fetchColumn();
    ?>
    <p>@<?php echo htmlspecialchars($seller_username); ?></p>
    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
    <p>€<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
    <p>Size <?php echo htmlspecialchars($item['item_size']); ?></p>
</div>

    <?php endforeach; ?>
</div>

    </main>

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
    <script>
       function updateSubcategories() {
    var categoryCheckboxes = document.querySelectorAll('input[name="category"]:checked');
    var selectedCategories = Array.from(categoryCheckboxes).map(cb => cb.value);
    var subcategoryContainer = document.getElementById('subcategory-container');
    subcategoryContainer.innerHTML = ''; // Clear previous subcategory options

    var options = {
        'dresses': ['Mini', 'Midi', 'Maxi'],
        'coats': ['Winter', 'Summer', 'Raincoat'],
        'shoes': ['Sneakers', 'Boots', 'Sandals', 'Heels'],
        'jeans': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
        'skirts': ['Mini', 'Midi', 'Maxi'],
        'shorts': ['Short length', 'Mid length'],
        'tops': ['T-shirts', 'Blouses', 'Crop tops', 'Shirts'],
        'pants': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
        'swimwear': ['Bikini', 'One-piece']
    };

    var subcategories = new Set();
    selectedCategories.forEach(category => {
        if (options[category]) {
            options[category].forEach(sub => subcategories.add(sub));
        }
    });

    subcategories.forEach(sub => {
        var label = document.createElement('label');
        var checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = sub.toLowerCase();
        checkbox.name = 'subcategory';
        label.appendChild(checkbox);
        label.append(sub);
        subcategoryContainer.appendChild(label);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var categoryCheckboxes = document.querySelectorAll('input[name="category"]');
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSubcategories);
    });
    updateSubcategories();
});


        </script>
        <script>
            const productsPerPage = 9;
            let currentPage = 1;
        
            function paginateProducts() {
                const products = document.querySelectorAll('.product');
                const totalProducts = products.length;
                const totalPages = Math.ceil(totalProducts / productsPerPage);
        
                products.forEach(product => {
                    product.style.display = 'none';
                });
        
                
                const startIndex = (currentPage - 1) * productsPerPage;
                const endIndex = startIndex + productsPerPage;
        
                
                for (let i = startIndex; i < endIndex && i < totalProducts; i++) {
                    products[i].style.display = 'block';
                }
        
                // Mostra o número correto da página
                document.getElementById('pageNumber').textContent = `${currentPage} / ${totalPages}`;
            }
        
            function changePage(step) {
                const products = document.querySelectorAll('.product');
                const totalProducts = products.length;
                const totalPages = Math.ceil(totalProducts / productsPerPage);
        
                // Atualiza página
                currentPage += step;
                if (currentPage < 1) {
                    currentPage = 1;
                } else if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
        
                paginateProducts();
            }
        
            document.addEventListener('DOMContentLoaded', function() {
                updateSubcategories();
                paginateProducts(); 
            });
        </script>

<script>
    function sortProducts() {
    var sortBy = document.getElementById('sort-price').value;
    var container = document.querySelector('.products');
    var products = Array.from(container.querySelectorAll('.product'));

    products.sort(function(a, b) {
        // Correctly parse prices as floats
        var priceA = parseFloat(a.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));
        var priceB = parseFloat(b.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));

        if (sortBy === 'low-to-high') {
            return priceA - priceB;
        } else if (sortBy === 'high-to-low') {
            return priceB - priceA;
        }
        return 0;
    });

    // Re-append sorted products
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }

    products.forEach(function(product) {
        container.appendChild(product);
    });
}

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
