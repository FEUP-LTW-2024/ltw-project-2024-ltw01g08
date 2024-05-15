<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sort = filter_input(INPUT_GET, 'sort', 513);
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";
    $categories = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $conditions = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $sizes = filter_input(INPUT_GET, 'size', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);  // Changed to handle an array of sizes
    $minPrice = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
    $maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);

    // Initialize the SQL query
    $sql = "SELECT * FROM Item WHERE department_id = 123";
    $params = [];

    // conditions for price
    if ($minPrice !== false && $minPrice != null) {
        $sql .= " AND price >= ?";
        $params[] = $minPrice;
    }

    if ($maxPrice !== false && $maxPrice != null) {
        $sql .= " AND price <= ?";
        $params[] = $maxPrice;
    }

    // Process categories
    if (!empty($categories) && !in_array('all', $categories)) {
        $placeholders = implode(', ', array_fill(0, count($categories), '?'));
        $sql .= " AND category_id IN (SELECT id FROM Category WHERE c_name IN ($placeholders) AND department_id = 123)";
        foreach ($categories as $category) {
            $params[] = $category;
        }
    }
    
    


    // Process conditions
    if (!empty($conditions)) {
        $conditionPlaceholders = implode(', ', array_fill(0, count($conditions), '?'));
        $sql .= " AND condition IN ($conditionPlaceholders)";
        foreach ($conditions as $condition) {
            $params[] = $condition;
        }
    }

    // Process sizes
    if (!empty($sizes)) {
        $sizePlaceholders = implode(', ', array_fill(0, count($sizes), '?'));
        $sql .= " AND item_size IN ($sizePlaceholders)";
        foreach ($sizes as $size) {
            $params[] = $size;
        }
    }

    //  sorting
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
            <span class="logo"><a href="../index.php" style="color: inherit; text-decoration: none;">ELITE FINDS</a></span>
            <div class="actions">
                <span>H</span>
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.html">User Profile</a>
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
                <li><a href="women_section.php">Women</a></li> 
                <li class="pink-highlight"><a href="men_section.html">Men</a></li> 
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
            <form id="filters">
                <fieldset>
                    <legend>Category</legend>
                    <label><input type="checkbox" value="all" name="category" checked>All</label>
                    <label><input type="checkbox" value="coats" name="category">Coats</label>
                    <label><input type="checkbox" value="shirts" name="category">Shirts</label>
                    <label><input type="checkbox" value="shoes" name="category">Shoes</label>
                    <label><input type="checkbox" value="jeans" name="category">Jeans</label>
                    <label><input type="checkbox" value="pants" name="category">Pants</label>
                    <label><input type="checkbox" value="shorts" name="category">Shorts</label>
                    <label><input type="checkbox" value="swimwear" name="category">Swimwear</label>
                </fieldset>
    
                <label for="subcategory">Subcategory</label>
                <select id="subcategory">
                </select>
    
                <label for="condition">Condition</label>
                <select id="condition">
                    <option value="default">All</option>
                    <option value="Excelent">Excelent</option>
                    <option value="Very good">Very good</option>
                    <option value="Good">Good</option>
                    <option value="Bad">Bad</option>
                </select>
    
                <label for="size">Size</label>
                <select id="size">
                    <option value="all">All</option>
                    <option value="32">32 EU</option>
                    <option value="34">36 EU</option>
                    <option value="36">36 EU</option>
                    <option value="38">38 EU</option>
                    <option value="40">40 EU</option>
                    <option value="42">42 EU</option>
                </select>
    
                <label for="price">Price</label>
                <input type="text" id="price" placeholder="Min Price">
                <input type="text" id="price" placeholder="Max Price">
            </form>
           

        </aside>

    <div class="products">
    <?php foreach ($items as $item):
        // Assuming you have a seller_id and need to fetch the seller's username for each item
        $seller_username_stmt = $pdo->prepare("SELECT username FROM User WHERE id = ?");
        $seller_username_stmt->execute([$item['seller_id']]);
        $seller_username = $seller_username_stmt->fetchColumn();

        // If no username was found, use a placeholder or empty string
        $seller_username = $seller_username ?: 'Unknown';  // Default to 'Unknown' if no username is found
        $image_url = "../images/items/item{$item['id']}_1.png";
    ?>
        <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="product-link">
            <div class="product">
                <p>@<?php echo htmlspecialchars($seller_username); ?></p>
                <h3><?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?></h3>
                <div class="image-container">
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?>">
                </div>
                <p>€<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
                <p>Size <?php echo htmlspecialchars($item['item_size'] ?? 'N/A'); ?></p>
            </div>
        </a>
    <?php endforeach; ?>
    </div>
        

        <div class="pagination">
            <button onclick="changePage(-1)">Prev</button>
            <span id="pageNumber">1</span>
            <button onclick="changePage(1)">Next</button>
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
    var subcategory = document.getElementById('subcategory');
    subcategory.innerHTML = '';

    var options = {
        'shirts': ['Long Sleeve', 'Short Sleeve', 'Mid Sleeve'],
                'jeans': ['Lose Fit','Skinny Fit'],
                'pants': ['Lose Fit','Skinny Fit'],
                'shorts': ['Jeans','Other Materials'],
                'swimwear': ['Neutral', 'Patterns'],
                'coats': ['Winter', 'Summer', 'Raincoat'],
                'shoes': ['Sneakers', 'Boots', 'Sandals', 'Loafers'],
    };

    var subcategories = new Set();
    selectedCategories.forEach(category => {
        if (options[category]) {
            options[category].forEach(sub => subcategories.add(sub));
        }
    });

    subcategories.forEach(sub => {
        var newOption = document.createElement('option');
        newOption.value = sub.toLowerCase();
        newOption.textContent = sub;
        subcategory.appendChild(newOption);
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

function resetFilters() {
    // Uncheck all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    // Clear text inputs
    document.querySelectorAll('input[type="text"]').forEach(input => {
        input.value = '';
    });

    // Submit the form
    document.getElementById('filters').submit();
}


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
