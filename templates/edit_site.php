<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize PDO for database connection
try {
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_TIMEOUT, 10); // Set timeout to 10 seconds
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Fetch all departments
$sql_dep = "SELECT * FROM Department";
$stmt = $pdo->prepare($sql_dep);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories linked with their departments for better context in selection
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
    $inputField = filter_input(INPUT_POST, 'inputField', FILTER_SANITIZE_STRING);
    $categoryId = $_POST['category'] ?? null;

    try {
        $pdo->beginTransaction();

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
                $stmt = $pdo->prepare("INSERT INTO ItemConditions (condition_description) VALUES (?)");
                $stmt->execute([$inputField]);
                break;
            case 'size':
                $stmt = $pdo->prepare("INSERT INTO ItemSizes (size_description) VALUES (?)");
                $stmt->execute([$inputField]);
                break;
        }

        $pdo->commit();
        $_SESSION['success_message'] = "Change successful!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    }

    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the form post submission
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

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

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

    <script>
        function toggleSelects() {
            var editOption = document.getElementById("editOption").value;
            var departmentSelect = document.getElementById("departmentSelect");
            var categorySelect = document.getElementById("categorySelect");

            departmentSelect.style.display = (editOption === "category") ? "block" : "none";
            categorySelect.style.display = (editOption === "subcategory") ? "block" : "none";
        }
    </script>

</body>
</html>
