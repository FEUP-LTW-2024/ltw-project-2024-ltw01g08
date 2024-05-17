<?php
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_dep = "SELECT * FROM Department";
    $params_dep = [];

    $sql_cat = "SELECT * FROM Department";
    $params_cat = [];

    $stmt = $pdo->prepare($sql_dep);
    $stmt->execute($params_dep);
    $departments_=$stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare($sql_cat);
    $stmt->execute($params_cat);
    $categories_=$stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <select id="editOption" name="editOption" onchange="toggleDepartmentSelect()">
                    <option value="" disabled selected>Select Option</option>
                    <option value="category">Add Category</option>
                    <option value="subcategory">Add Subcategory</option>
                    <option value="condition">Add Condition</option>
                    <option value="size">Add Size</option>
                </select>
            </div>

            <div id="departmentSelect" class="form-row" style="display: none;">
            <label for="department">Select Department to Edit:</label>
                <select id="department" name="department">
                    <option value="" disabled selected>Select Option</option>
                        <?php foreach ($departments_ as $department): ?>
                            <option value="department"> 
                                <?php echo htmlspecialchars($department['d_name']); ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </label>

            </div>


            <div id="categorySelect" class="form-row" style="display: none;">
            <label for="category">Select Category to Edit:</label>
                <select id="category" name="category">
                    <option value="" disabled selected>Select Option</option>
                        <?php foreach ($categories_ as $category_): ?>
                            <option value="category"> 
                                <?php echo htmlspecialchars($category_['c_name']); ?>
                            </option>
                        <?php endforeach; ?>
                </select>
            </label>

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
        function toggleDepartmentSelect() {
            var editOption = document.getElementById("editOption").value;
            var departmentSelect = document.getElementById("departmentSelect");

            if (editOption === "category") {
                departmentSelect.style.display = "block";
            } else {
                departmentSelect.style.display = "none";
            }
        }

        function toggleCategorySelect() {
            var editOption = document.getElementById("editOption").value;
            var departmentSelect = document.getElementById("categorySelect");

            if (editOption === "subcategory") {
                departmentSelect.style.display = "block";
            } else {
                departmentSelect.style.display = "none";
            }
        }
    </script>
</body>
</html>
