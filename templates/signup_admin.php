<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm-password', FILTER_SANITIZE_STRING);
    $admin_code = filter_input(INPUT_POST, 'admin-code', FILTER_SANITIZE_STRING);
    $profile_pic = $_FILES['profile-pic'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header('Location: signup_admin.php');
        exit;
    }

    // Validate the admin code
    $valid_admin_code = 'iamanadmin2024';
    if ($admin_code !== $valid_admin_code) {
        $_SESSION['error_message'] = "Invalid admin code.";
        header('Location: signup_admin.php');
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile picture upload
    $profile_pic_url = null;
    if ($profile_pic['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../images/profile_pics/";
        $target_file = $target_dir . basename($profile_pic['name']);
        if (move_uploaded_file($profile_pic['tmp_name'], $target_file)) {
            $profile_pic_url = $target_file;
        } else {
            $_SESSION['error_message'] = "Failed to upload profile picture.";
            header('Location: signup_admin.php');
            exit;
        }
    }

    try {
        $pdo = new PDO('sqlite:../database/database.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction(); // start the transaction

        // Insert user into the User table
        $sql = "INSERT INTO User (first_name, last_name, username, email, user_password, user_address, profile_picture) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name, $username, $email, $hashed_password, $address, $profile_pic_url]);

        // Get the user ID of the newly created user
        $user_id = $pdo->lastInsertId();

        // Insert user into the Admin table
        $sql_admin = "INSERT INTO Admin (user_id) VALUES (?)";
        $stmt_admin = $pdo->prepare($sql_admin);
        $stmt_admin->execute([$user_id]);

        $pdo->commit(); // commit the transaction

        $_SESSION['success_message'] = "Account created successfully!";
        header('Location: signup_admin.php');
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack(); // roll back the transaction if something failed
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: signup_admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up as Admin - Elite Finds</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
    <div class="form-signup">
        <h2>Create an admin account</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        <form action="signup_admin.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group">
                <label for="profile-pic">Profile Picture:</label>
                <input type="file" id="profile-pic" name="profile-pic" accept="image/*">
            </div>
            <div class="form-group">
                <label for="admin-code">Admin Code:</label>
                <input type="text" id="admin-code" name="admin-code" required>
            </div>
            <button type="submit">SIGN UP</button>
        </form>
        <p>Already have an account? <a href="login.html">Login</a></p>
    </div>
</body>
</html>