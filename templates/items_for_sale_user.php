<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT Item.id, Item.title, Item.price, ItemSizes.size_description AS item_size, Item.color, Category.c_name AS category_name, Department.d_name AS department_name
    FROM Item
    JOIN ItemSizes ON Item.item_size = ItemSizes.id
    JOIN Category ON Item.category_id = Category.id
    JOIN Department ON Category.department_id = Department.id
    WHERE Item.seller_id = ?
    AND Item.id NOT IN (SELECT item_id FROM 'Transaction')
");
$stmt->execute([$userId]);
$itemsForSale = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="products">
    <?php if (!empty($itemsForSale)): ?>
        <?php foreach ($itemsForSale as $item): 
            $image_url = "../images/items/item{$item['id']}_1.png"; ?>
            <div class="product" id="product-<?php echo $item['id']; ?>">
                <a href="product_page.php?product_id=<?php echo $item['id']; ?>">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <div class="image-container">
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    </div>
                    <p>€<?php echo number_format($item['price'], 2); ?></p>
                    <p>Size: <?php echo htmlspecialchars($item['item_size']); ?></p>
                    <p>Color: <?php echo htmlspecialchars($item['color']); ?></p>
                    <p>Category: <?php echo htmlspecialchars($item['category_name']); ?></p>
                    <p>Department: <?php echo htmlspecialchars($item['department_name']); ?></p>
                </a>
                <form onsubmit="deleteItem(event, <?php echo $item['id']; ?>)">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have no items for sale.</p>
    <?php endif; ?>
</div>

<script>
function deleteItem(event, itemId) {
    event.preventDefault();

    if (confirm('Are you sure you want to delete this item?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../actions/delete_item.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.status === 200 && this.responseText.trim() === "Success") {
                window.location.href = "user_page.php";
                alert('Item deleted successfully!');
            } else {
                alert('Error deleting item: ' + this.responseText);
            }
        };
        xhr.send("item_id=" + itemId);
    }
}
</script>
