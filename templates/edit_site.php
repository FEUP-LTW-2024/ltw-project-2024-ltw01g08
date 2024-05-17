<?php
    $pdo = new PDO('sqlite:../database/database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_dep = "SELECT * FROM Department";
    $params_dep = [];



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
                <select id="editOption" name="editOption" onchange="toggleDepartmentSelect(); toggleCategorySelect(this.value);">
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
                        <option value="<?php echo $department['id']; ?>"> 
                            <?php echo htmlspecialchars($department['d_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="categorySelect" class="form-row" style="display: none;">
                <label for="category">Select Category to Edit:</label>
                <select id="category" name="category">
                    <option value="" disabled selected>Select Option</option>
                    <!-- Categories will be populated dynamically based on the selected department -->
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
        function toggleDepartmentSelect() {
            var editOption = document.getElementById("editOption").value;
            var departmentSelect = document.getElementById("departmentSelect");

            if (editOption === "category") {
                departmentSelect.style.display = "block";
            } else {
                departmentSelect.style.display = "none";
            }
        }
        function toggleCategorySelect(editOption) {
            var categorySelect = document.getElementById("categorySelect");

            if (editOption === "subcategory") {
                categorySelect.style.display = "block";
                var departmentId = document.getElementById("department").value;
                // Call a function to fetch categories based on the selected department
                fetchCategories(departmentId);
            } else {
                categorySelect.style.display = "none";
            }
        }

        function fetchCategories(departmentId) {
    // Make an AJAX request to fetch categories
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_categories.php?departmentId=' + departmentId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parse the JSON response
            var categories = JSON.parse(xhr.responseText);

            // Get the category select element
            var categorySelect = document.getElementById('category');

            // Clear existing options
            categorySelect.innerHTML = '';

            // Add a default option
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select Category';
            categorySelect.appendChild(defaultOption);

            // Populate select options with categories
            categories.forEach(function(category) {
                var option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.c_name;
                categorySelect.appendChild(option);
            });
        }
    };
    xhr.send();
}

document.getElementById('department').addEventListener('change', function() {
    var departmentId = this.value;
    if (departmentId) {
        fetchCategories(departmentId);
    }
});
    </script>
</body>
</html>
