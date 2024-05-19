<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";

    $categories = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $conditions = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $sizes = filter_input(INPUT_GET, 'size', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $minPrice = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
    $maxPrice = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);

    $sql = "SELECT * FROM Item WHERE department_id = 127";
    $params = [];

    $sql_category = "SELECT * FROM Category WHERE department_id = 127";
    $params_category = [];

    if ($minPrice !== false && $minPrice != null) {
        $sql .= " AND price >= ?";
        $params[] = $minPrice;
    }

    if ($maxPrice !== false && $maxPrice != null) {
        $sql .= " AND price <= ?";
        $params[] = $maxPrice;
    }

    if (!empty($categories)) {
        $placeholders = implode(', ', array_fill(0, count($categories), '?'));
        $sql .= " AND category_id IN (SELECT id FROM Category WHERE c_name IN ($placeholders) AND department_id = 127)";
        foreach ($categories as $category) {
            $params[] = $category;
        }
    }

    if (!empty($conditions)) {
        $conditionPlaceholders = implode(', ', array_fill(0, count($conditions), '?'));
        $sql .= " AND condition IN ($conditionPlaceholders)";
        foreach ($conditions as $condition) {
            $params[] = $condition;
        }
    }


    // Sorting
    $sql .= " ORDER BY price $order";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare($sql_category);
    $stmt->execute($params_category);
    $categories_=$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acessories's Section - Elite Finds</title>
    <link rel="stylesheet" href="../css/women_section.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <form action="search_results.php" method="get">
                <input type="text" name="query" placeholder="Search" class="search-bar" required>
            </form>
            <span class="logo"><a href="../index.php" style="color: inherit; text-decoration: none;">ELITE FINDS</a></span>
            <div class="actions">
                <span>H</span>
                <span class="profile-dropdown">
                    <img id="profile-icon" src="../images/icons/profile.png" alt="Profile">
                    <div id="dropdown-menu" class="dropdown-content">
                        <a href="../templates/user_page.php">User Profile</a>
                        <a href="../templates/account_info.php">Account Info</a>
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
                <li><a href="men_section.php">Men</a></li> 
                <li><a href="kids_section.php">Kids</a></li> 
                <li><a href="bags_section.php">Bags</a></li> 
                <li><a href="jewelry_section.php">Jewelry</a></li> 
                <li class="pink-highlight"><a href="accessories_section.php">Accessories</a></li> 
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
            <form id="filters" method="GET" action="accessories_section.php">
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
                <a href="product_page.php?product_id=<?php echo htmlspecialchars($item['id']); ?>" class="product-link">
                    <div class="product">
                        <p>@<?php echo htmlspecialchars($seller_username); ?></p>
                        <h3><?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?></h3>
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? 'No title available'); ?>">
                        </div>
                        <p>€<?php echo htmlspecialchars(number_format($item['price'], 2)); ?></p>
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
        function resetFilters() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });

            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.value = '';
            });

            document.getElementById('filters').submit();
        }

        function sortProducts() {
            var sortBy = document.getElementById('sort-price').value;
            var container = document.querySelector('.products');
            var products = Array.from(container.querySelectorAll('.product'));

            products.sort(function(a, b) {
                var priceA = parseFloat(a.querySelector('p:last-child').textContent.replace(/[^\d.]/g, ''));
                var priceB = parseFloat(b.querySelector('p:last-child').textContent.replace(/[^\d.]/g, ''));

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


        const productsPerPage = 9;
        let currentPage = 1;

        function paginateProducts() {
            const products = document.querySelectorAll('.product');
            const totalProducts = products.length;
            const totalPages = Math.ceil(totalProducts / productsPerPage);

            products.forEach((product, index) => {
                product.style.display = (index >= (currentPage - 1) * productsPerPage && index < currentPage * productsPerPage) ? 'block' : 'none';
            });

            document.getElementById('pageNumber').textContent = `${currentPage} / ${totalPages}`;
        }

        function changePage(step) {
            const products = document.querySelectorAll('.product');
            const totalProducts = products.length;
            const totalPages = Math.ceil(totalProducts / productsPerPage);

            currentPage = Math.max(1, Math.min(totalPages, currentPage + step));
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
