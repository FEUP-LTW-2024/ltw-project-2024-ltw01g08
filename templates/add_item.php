<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Elite Finds</title>
    <link rel="stylesheet" href="../css/add_item.css">
</head>
<body>
        <div class="main">
        <h1>Add a New Item</h1>
        <form action="submit_item.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-row">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-row">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="" disabled selected>Select Department</option>
                    <?php
                    $deptStmt = $pdo->query("SELECT * FROM Department");
                    while ($row = $deptStmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-row">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="" disabled selected>Select Category</option>
                    <!-- fazer -->
                </select>
            </div>
            <div class="form-row">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" required>
            </div>
            <div class="form-row">
                <label for="size">Size</label>
                <input type="text" id="size" name="size" required>
            </div>
            <div class="form-row">
                <label for="color">Color</label>
                <input type="text" id="color" name="color" required>
            </div>
            <div class="form-row">
                <label for="condition">Condition</label>
                <select id="condition" name="condition" required>
                    <option value="" disabled selected>Select Condition</option>
                    <!-- fazer povoamento -->
                </select>
            </div>
            <div class="form-row">
                <label for="price">Price (€)</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>
            <div class="form-row">
                <label for="image">Image Upload</label>
                <input type="file" id="image" name="image" required>
            </div>
            <button type="submit">Add Item</button>
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
        function toggleProfileDropdown() {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            dropdownContainer.classList.toggle('show');
        }

        document.getElementById('profile-icon').addEventListener('click', function(event) {
            event.stopPropagation();
            toggleProfileDropdown();
        });

        window.addEventListener('click', function(event) {
            const dropdownContainer = document.querySelector('.profile-dropdown');
            if (dropdownContainer.classList.contains('show')) {
                dropdownContainer.classList.remove('show');
            }
        });
    </script>
</body>
</html>
