<?php
$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch departments
$sql_dep = "SELECT * FROM Department";
$stmt = $pdo->prepare($sql_dep);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
// Fetch categories with their respective departments
$sql_cat = "
    SELECT Category.id, Category.c_name, Department.d_name 
    FROM Category 
    JOIN Department ON Category.department_id = Department.id
";
$stmt = $pdo->prepare($sql_cat);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $editOption = $_POST['editOption'];
    $inputField = $_POST['inputField'];
    $categoryId = $_POST['category'] ?? null;

    switch ($editOption) {
        case 'department':
            $stmt = $pdo->prepare("INSERT INTO Department (d_name) VALUES (?)");
            $stmt->execute([$inputField]);
            break;
        case 'category':
            $departmentId = $_POST['department'];
            $stmt = $pdo->prepare("INSERT INTO Category (c_name, department_id) VALUES (?, ?)");
            $stmt->execute([$inputField, $departmentId]);
            break;
        case 'subcategory':
            $stmt = $pdo->prepare("INSERT INTO Subcategory (subc_name, category_id) VALUES (?, ?)");
            $stmt->execute([$inputField, $categoryId]);
            break;
        case 'condition':
            $stmt = $pdo->prepare("INSERT INTO Conditions (description, category_id) VALUES (?, ?)");
            $stmt->execute([$inputField, $categoryId]);
            break;
        case 'size':
            $stmt = $pdo->prepare("INSERT INTO Sizes (size, category_id) VALUES (?, ?)");
            $stmt->execute([$inputField, $categoryId]);
            break;
    }

    header("Location: ".$_SERVER['PHP_SELF']); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Site - Elite Finds</title>
    <link rel="stylesheet" href="../css/add_item.css">
</head>
<body>
    <div class="main">
        <h1>Edit Site</h1>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <label for="editOption">Select Option to Add:</label>
                <select id="editOption" name="editOption" onchange="toggleSelects()">
                    <option value="" disabled selected>Select Option</option>
                    <option value="department">Add Department</option>
                    <option value="category">Add Category</option>
                    <option value="subcategory">Add Subcategory</option>
                    <option value="condition">Add Condition</option>
                    <option value="size">Add Size</option>
                </select>
            </div>

            <div id="departmentSelect" class="form-row" style="display: none;">
                <label for="department">Select Department:</label>
                <select id="department" name="department">
                    <option value="" disabled selected>Select Department</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['d_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="categorySelect" class="form-row" style="display: none;">
                <label for="category">Select Category:</label>
                <select id="category" name="category">
                    <option value="" disabled selected>Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['c_name']) . " (" . htmlspecialchars($category['d_name']) . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label for="inputField">Enter Value:</label>
                <input type="text" id="inputField" name="inputField" required>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

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
        function toggleSelects() {
            var editOption = document.getElementById("editOption").value;
            var departmentSelect = document.getElementById("departmentSelect");
            var categorySelect = document.getElementById("categorySelect");

            departmentSelect.style.display = (editOption === "category" || editOption === "department") ? "block" : "none";
            categorySelect.style.display = (editOption === "subcategory" || editOption === "condition" || editOption === "size") ? "block" : "none";
        }
    </script>
</body>
</html>

