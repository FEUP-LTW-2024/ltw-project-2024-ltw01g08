<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all departments
    $sql_departments = "SELECT * FROM Department";
    $stmt_departments = $pdo->prepare($sql_departments);
    $stmt_departments->execute();
    $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);

    // Determine current department
    $current_department_id = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
    if (!$current_department_id) {
        $current_department_id = $departments[0]['id'];
    }

    // Fetch categories for the current department
    $sql_categories = "SELECT * FROM Category WHERE department_id = ?";
    $stmt_categories = $pdo->prepare($sql_categories);
    $stmt_categories->execute([$current_department_id]);
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    $sql_subcategories = "SELECT Subcategory.id, Subcategory.subc_name FROM Subcategory 
    INNER JOIN Category ON Subcategory.category_id = Category.id 
    ";

    $stmt_subcategories = $pdo->prepare($sql_subcategories);
    $stmt_subcategories->execute();
    $subcategories = $stmt_subcategories->fetchAll(PDO::FETCH_ASSOC);

    // Fetch items for the current department with filtering and sorting
    $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";
    $categories_filter = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $conditions = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $sizes = filter_input(INPUT_GET, 'size', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $minPrice = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
    $maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);

    // Initialize the SQL query
    $sql = "SELECT * FROM Item WHERE department_id = ?";
    $params = [$current_department_id];

    // Apply filters
    if ($minPrice !== false && $minPrice != null) {
        $sql .= " AND price >= ?";
        $params[] = $minPrice;
    }

    if ($maxPrice !== false && $maxPrice != null) {
        $sql .= " AND price <= ?";
        $params[] = $maxPrice;
    }

    if (!empty($categories_filter) && !in_array('all', $categories_filter)) {
        $placeholders = implode(', ', array_fill(0, count($categories_filter), '?'));
        $sql .= " AND category_id IN (SELECT id FROM Category WHERE c_name IN ($placeholders) AND department_id = ?)";
        foreach ($categories_filter as $category) {
            $params[] = $category;
        }
        $params[] = $current_department_id;
    }

    if (!empty($conditions)) {
        $conditionPlaceholders = implode(', ', array_fill(0, count($conditions), '?'));
        $sql .= " AND condition IN ($conditionPlaceholders)";
        foreach ($conditions as $condition) {
            $params[] = $condition;
        }
    }

    if (!empty($sizes)) {
        $sizePlaceholders = implode(', ', array_fill(0, count($sizes), '?'));
        $sql .= " AND item_size IN ($sizePlaceholders)";
        foreach ($sizes as $size) {
            $params[] = $size;
        }
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
    <title>Department - Elite Finds</title>
    <link rel="stylesheet" href="../css/women_section.css">
</head>
<body>
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

    <main>
        <nav class="category-bar">
            <ul>
                <?php foreach ($departments as $department): ?>
                    <li class="<?php echo $current_department_id === $department['id'] ? 'pink-highlight' : ''; ?>">
                        <a href="?department_id=<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['d_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <aside class="sorter-sidebar">
            <h2>Sort By</h2>
            <form id="sorters" method="GET">
                <input type="hidden" name="department_id" value="<?php echo $current_department_id; ?>">
                <label for="sort-price">Price:</label>
                <select id="sort-price" name="sort" onchange="sortProducts();">
                    <option value="default">--</option>
                    <option value="low-to-high" <?php if ($sort === 'low-to-high') echo 'selected'; ?>>Low to High</option>
                    <option value="high-to-low" <?php if ($sort === 'high-to-low') echo 'selected'; ?>>High to Low</option>
                </select>
            </form>
        </aside>

        <aside class="filter-sidebar">
            <h2>Filter By</h2>
            <form id="filters" method="GET">
                <input type="hidden" name="department_id" value="<?php echo $current_department_id; ?>">
                <fieldset>
                    <legend>Category</legend>
                    <?php foreach ($categories as $category): ?>
                        <label>
                            <input type="checkbox" value="<?php echo htmlspecialchars($category['c_name']); ?>" name="category[]">
                            <?php echo htmlspecialchars($category['c_name']); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <fieldset>
                <legend>Subcategory</legend>
                <div id="subcategory-container">
                    <?php if (!empty($subcategories)): ?>
                        <?php foreach ($subcategories as $subcategory): ?>
                            <label>
                                <input type="checkbox" value="<?php echo htmlspecialchars($subcategory['id']); ?>" name="subcategory[]">
                                <?php echo htmlspecialchars($subcategory['subc_name']); ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </fieldset>

                <fieldset>
                    <legend>Condition</legend>
                    <div id="condition-container">
                        <label><input type="checkbox" name="condition[]" value="Excellent">Excellent</label>
                        <label><input type="checkbox" name="condition[]" value="Very good">Very good</label>
                        <label><input type="checkbox" name="condition[]" value="Good">Good</label>
                        <label><input type="checkbox" name="condition[]" value="Bad">Bad</label>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Size</legend>
                    <label><input type="checkbox" name="size[]" value="XS">XS</label>
                    <label><input type="checkbox" name="size[]" value="S">S</label>
                    <label><input type="checkbox" name="size[]" value="M">M</label>
                    <label><input type="checkbox" name="size[]" value="L">L</label>
                    <label><input type="checkbox" name="size[]" value="XL">XL</label>
                </fieldset>

                <label for="min-price">Min Price:</label>
                <input type="text" id="min-price" name="min_price" placeholder="Min Price">

                <label for="max-price">Max Price:</label>
                <input type="text" id="max-price" name="max_price" placeholder="Max Price">

                <button type="button" class="reset" onclick="resetFilters()">Reset Filters</button>
                <button type="submit">Apply Filters</button>
            </form>
        </aside>

        <div class="products">
            <?php foreach ($items as $item):
                $seller_username_stmt = $pdo->prepare("SELECT username FROM User WHERE id = ?");
                $seller_username_stmt->execute([$item['seller_id']]);
                $seller_username = $seller_username_stmt->fetchColumn();
                $seller_username = $seller_username ?: 'Unknown';
                $image_url = "../images/items/item{$item['id']}_1.png";
            ?>
                <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="product-link" data-product-id="<?php echo htmlspecialchars($item['id']); ?>">
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
    </main>

    <footer>
        <ul class="footer-list">
            <li><a href="shipping.html">Shipping</a></li>
            <li><a href="faq.html">FAQ</a></li>
            <li><a href="our_story.html">Our Story</a></li>
            <li><a href="returns.html">Returns</a></li>
            <li><a href="contact_us.html">Contact Us</a></li>
        </ul>
    </footer>


    
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


document.addEventListener('DOMContentLoaded', function() {
    const productLinks = document.querySelectorAll('.product-link');
    productLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const productId = this.getAttribute('data-product-id');
            window.location.href = `product_page.php?product_id=${productId}`;
        });
    });
});


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








