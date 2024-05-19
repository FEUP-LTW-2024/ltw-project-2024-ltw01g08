<?php
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all departments
    $sql_departments = "SELECT * FROM Department";
    $stmt_departments = $pdo->prepare($sql_departments);
    $stmt_departments->execute();
    $departments = $stmt_departments->fetchAll(PDO::FETCH_ASSOC);

    $current_department_id = filter_input(INPUT_GET, 'department_id', FILTER_VALIDATE_INT);
    if (!$current_department_id) {
        $current_department_id = $departments[0]['id'];
    }

    // Fetch categories and subcategories
    $sql_categories = "SELECT * FROM Category WHERE department_id = ?";
    $stmt_categories = $pdo->prepare($sql_categories);
    $stmt_categories->execute([$current_department_id]);
    $categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

    $sql_subcategories = "SELECT Subcategory.id, Subcategory.subc_name FROM Subcategory 
                          INNER JOIN Category ON Subcategory.category_id = Category.id";
    $stmt_subcategories = $pdo->prepare($sql_subcategories);
    $stmt_subcategories->execute();
    $subcategories = $stmt_subcategories->fetchAll(PDO::FETCH_ASSOC);

    // Fetch conditions and sizes
    $sql_conditions = "SELECT * FROM ItemConditions";
    $stmt_conditions = $pdo->prepare($sql_conditions);
    $stmt_conditions->execute();
    $conditions = $stmt_conditions->fetchAll(PDO::FETCH_ASSOC);

    $sql_sizes = "SELECT * FROM ItemSizes";
    $stmt_sizes = $pdo->prepare($sql_sizes);
    $stmt_sizes->execute();
    $sizes = $stmt_sizes->fetchAll(PDO::FETCH_ASSOC);

    // Initialize the SQL query for items
    $sort = filter_input(INPUT_GET, 'sort');
    $order = ($sort === 'high-to-low') ? "DESC" : "ASC";
    $sql = "SELECT Item.*, ItemSizes.size_description FROM Item
            LEFT JOIN ItemSizes ON Item.item_size = ItemSizes.id
            WHERE department_id = ?";

    $params = [$current_department_id]; // parameters for SQL execution

    // Add filters to the SQL query
    $category_filter = filter_input(INPUT_GET, 'category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if ($category_filter) {
        $category_placeholders = implode(',', array_fill(0, count($category_filter), '?'));
        $sql .= " AND category_id IN ($category_placeholders)";
        $params = array_merge($params, $category_filter);
    }

    $subcategory_filter = filter_input(INPUT_GET, 'subcategory', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if ($subcategory_filter) {
        $subcategory_placeholders = implode(',', array_fill(0, count($subcategory_filter), '?'));
        $sql .= " AND subcategory_id IN ($subcategory_placeholders)";
        $params = array_merge($params, $subcategory_filter);
    }

    $condition_filter = filter_input(INPUT_GET, 'condition', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if ($condition_filter) {
        $condition_placeholders = implode(',', array_fill(0, count($condition_filter), '?'));
        $sql .= " AND condition IN ($condition_placeholders)";
        $params = array_merge($params, $condition_filter);
    }

    $size_filter = filter_input(INPUT_GET, 'size', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    if ($size_filter) {
        $size_placeholders = implode(',', array_fill(0, count($size_filter), '?'));
        $sql .= " AND item_size IN ($size_placeholders)";
        $params = array_merge($params, $size_filter);
    }

    $min_price = filter_input(INPUT_GET, 'min_price', FILTER_VALIDATE_FLOAT);
    if ($min_price) {
        $sql .= " AND price >= ?";
        $params[] = $min_price;
    }

    $max_price = filter_input(INPUT_GET, 'max_price', FILTER_VALIDATE_FLOAT);
    if ($max_price) {
        $sql .= " AND price <= ?";
        $params[] = $max_price;
    }

    // Execute SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If sorting is requested, reorder the items array
    if ($sort) {
        usort($items, function ($item1, $item2) use ($order) {
            if ($order === "DESC") {
                return $item2['price'] <=> $item1['price'];
            } else {
                return $item1['price'] <=> $item2['price'];
            }
        });
    }
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
                            <input type="checkbox" value="<?php echo htmlspecialchars($category['id']); ?>" name="category[]" <?php if (in_array($category['id'], (array)$category_filter)) echo 'checked'; ?>>
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
                                    <input type="checkbox" value="<?php echo htmlspecialchars($subcategory['id']); ?>" name="subcategory[]" <?php if (in_array($subcategory['id'], (array)$subcategory_filter)) echo 'checked'; ?>>
                                    <?php echo htmlspecialchars($subcategory['subc_name']); ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Condition</legend>
                    <div id="condition-container">
                        <?php foreach ($conditions as $condition): ?>
                            <label>
                                <input type="checkbox" value="<?php echo htmlspecialchars($condition['id']); ?>" name="condition[]" <?php if (in_array($condition['id'], (array)$condition_filter)) echo 'checked'; ?>>
                                <?php echo htmlspecialchars($condition['condition_description']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Size</legend>
                    <?php foreach ($sizes as $size): ?>
                        <label>
                            <input type="checkbox" value="<?php echo htmlspecialchars($size['id']); ?>" name="size[]" <?php if (in_array($size['id'], (array)$size_filter)) echo 'checked'; ?>>
                            <?php echo htmlspecialchars($size['size_description']); ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <label for="min-price">Min Price:</label>
                <input type="text" id="min-price" name="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($min_price ?? ''); ?>">

                <label for="max-price">Max Price:</label>
                <input type="text" id="max-price" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($max_price ?? ''); ?>">

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
                        <p>Size <?php echo htmlspecialchars($item['size_description'] ?? 'N/A'); ?></p>
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

        function resetFilters() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
            document.querySelectorAll('input[type="text"]').forEach(input => input.value = '');
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
            const sortBy = document.getElementById('sort-price').value;
            const container = document.querySelector('.products');
            const productLinks = Array.from(container.querySelectorAll('.product-link'));

            productLinks.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.product p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));
                const priceB = parseFloat(b.querySelector('.product p:nth-last-child(2)').textContent.replace(/[^\d,.]/g, '').replace(',', '.'));

                return sortBy === 'low-to-high' ? priceA - priceB : priceB - priceA;
            });

            productLinks.forEach(link => container.appendChild(link));
        }

        function toggleProfileDropdown() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            dropdownContainer.classList.toggle('show');
        }

        document.getElementById('profile-icon').addEventListener('click', function(event) {
            event.stopPropagation();
            toggleProfileDropdown();
        });

        window.addEventListener('click', function() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
        });
    </script>
</body>
</html>
