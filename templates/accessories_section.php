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

    if (!empty($sizes)) {
        $sizePlaceholders = implode(', ', array_fill(0, count($sizes), '?'));
        $sql .= " AND size IN ($sizePlaceholders)";
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
    <title>Women's Section - Elite Finds</title>
    <link rel="stylesheet" href="../css/women_section.css">
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
                <select id="sort-price" name="sort" onchange="document.getElementById('filters').submit();">
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
                    <label><input type="checkbox" value="Belts" name="category[]">Belts</label>
                    <label><input type="checkbox" value="Sunglasses" name="category[]">Sunglasses</label>
                    <label><input type="checkbox" value="Hats" name="category[]">Hats</label>
                    <label><input type="checkbox" value="Scarfs" name="category[]">Scarfs</label>
                    <label><input type="checkbox" value="Wallets" name="category[]">Wallets</label>
                    <label><input type="checkbox" value="Watches" name="category[]">Watches</label>
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
            <button onclick="changePage(1)">Next</button
