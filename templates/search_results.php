<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch departments
    $sql_departments = "SELECT * FROM Department";
    $stmt_departments = $pdo->prepare($sql_departments);
    $stmt_departments->execute();
    $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);

    // Fetch categories for department 122 by default
    $sql_category = "SELECT * FROM Category WHERE department_id = 122";
    $stmt_category = $pdo->prepare($sql_category);
    $stmt_category->execute();
    $categories_ = $stmt_category->fetchAll(PDO::FETCH_ASSOC);

    $sort = filter_input(INPUT_GET, 'sort', 513);
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";

    $searchQuery = filter_input(INPUT_GET, 'query', 513);  // Corrected to use 'query'
    $categories = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $conditions = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $sizes = filter_input(INPUT_GET, 'size', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $minPrice = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
    $maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);
    $departmentsFilter = filter_input(INPUT_GET, 'departments', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    // Initialize the SQL query
    $sql = "SELECT * FROM Item WHERE 1=1";
    $params = [];

    // Process search query
    if (!empty($searchQuery)) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $searchQuery . '%';
    }

    // Process departments
    if (!empty($departmentsFilter)) {
        $placeholders = implode(', ', array_fill(0, count($departmentsFilter), '?'));
        $sql .= " AND department_id IN ($placeholders)";
        foreach ($departmentsFilter as $department) {
            $params[] = $department;
        }
    }

    // Process categories
    if (!empty($categories) && !in_array('all', $categories)) {
        $placeholders = implode(', ', array_fill(0, count($categories), '?'));
        $sql .= " AND category_id IN (SELECT id FROM Category WHERE c_name IN ($placeholders))";
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

    // conditions for price
    if ($minPrice !== false && $minPrice != null) {
        $sql .= " AND price >= ?";
        $params[] = $minPrice;
    }

    if ($maxPrice !== false && $maxPrice != null) {
        $sql .= " AND price <= ?";
        $params[] = $maxPrice;
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
    <link rel="stylesheet" href="../css/women_section.css">
</head>
<body>
    <header>

        <div class="top-bar">
            <form action="search_results.php" method="get">
                <input type="text" name="query" placeholder="Search" class="search-bar" id="search-bar" required>
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

                <input type="hidden" name="search" id="search-query">

                <fieldset>
                    <legend>Department</legend>
                    <?php foreach ($departments as $department): ?>
                        <label>
                            <input type="checkbox" value="<?php echo htmlspecialchars($department['id']); ?>" name="departments[]">
                            <?php echo htmlspecialchars($department['d_name']); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <fieldset>
                    <legend>Category</legend>
                    <?php foreach ($categories_ as $category): ?>
                        <label>
                            <input type="checkbox" value="<?php echo htmlspecialchars($category['c_name']); ?>" name="category[]">
                            <?php echo htmlspecialchars($category['c_name']); ?>
                        </label>
                    <?php endforeach; ?>
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
        <p>Search result for "<?php echo $searchQuery; ?>"</p>
            <?php foreach ($items as $item):
                // Fetch seller's username
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

        <div class="pagination">
            <button onclick="changePage(-1)">Prev</button>
            <span id="pageNumber">1</span>
            <button onclick="changePage(1)">Next</button>
        </div>
    </main>

    <footer>
        <div class="footer-section">
            <p>&copy;Elite Finds, 2024</p>
        </div>
    </footer>
    
    <script>
        function updateSubcategories() {
            var categoryCheckboxes = document.querySelectorAll('input[name="category[]"]:checked');
            var selectedCategories = Array.from(categoryCheckboxes).map(cb => cb.value);
            var subcategoryContainer = document.getElementById('subcategory-container');
            subcategoryContainer.innerHTML = ''; // Clear previous subcategory options

            var options = {
                'Dresses': ['Mini', 'Midi', 'Maxi'],
                'Coats': ['Winter', 'Summer', 'Raincoat'],
                'Shoes': ['Sneakers', 'Boots', 'Sandals', 'Heels'],
                'Jeans': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
                'Skirts': ['Mini', 'Midi', 'Maxi'],
                'Shorts': ['Short length', 'Mid length'],
                'Tops': ['T-shirts', 'Blouses', 'Crop tops', 'Shirts'],
                'Pants': ['Loose Fit', 'Skinny Fit', 'Bootcut'],
                'Swimwear': ['Bikini', 'One-piece']
            };

            selectedCategories.forEach(category => {
                if (options[category]) {
                    options[category].forEach(sub => {
                        var label = document.createElement('label');
                        var checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.value = sub;
                        checkbox.name = 'subcategory[]';
                        label.appendChild(checkbox);
                        label.append(sub);
                        subcategoryContainer.appendChild(label);
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var categoryCheckboxes = document.querySelectorAll('input[name="category[]"]');
            categoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSubcategories);
            });

            // Event listener for search input
            document.getElementById('search-bar').addEventListener('input', function() {
                document.getElementById('search-query').value = this.value;
            });

            // Initialize subcategories based on selected categories
            updateSubcategories();
        });

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
                var priceA = parseFloat(a.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));
                var priceB = parseFloat(b.querySelector('p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));

                if (sortBy === 'low-to-high') {
                    return priceA - priceB;
                } else if (sortBy === 'high-to-low') {
                    return priceB - priceA;
                }
                return 0;
            });

            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }

            products.forEach(function(product) {
                container.appendChild(product);
            });
        }

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
            document.getElementById('pageNumber').textContent = `${currentPage} / ${totalPages}`;
        }

        function changePage(step) {
            const products = document.querySelectorAll('.product');
            const totalProducts = products.length;
            const totalPages = Math.ceil(totalProducts / productsPerPage);

            currentPage += step;
            if (currentPage < 1) {
                currentPage = 1;
            } else if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            paginateProducts();
        }

        document.addEventListener('DOMContentLoaded', function() {
            paginateProducts();
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
