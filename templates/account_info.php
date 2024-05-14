<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../database/connection.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = getDatabaseConnection();  

    $fieldsToUpdate = [];
    $sql = "UPDATE User SET ";
    $params = [];

    foreach (['first_name', 'last_name', 'email', 'username', 'address'] as $field) {
        if (!empty($_POST[$field])) {
            $fieldsToUpdate[] = "$field = ?";
            $params[] = htmlspecialchars($_POST[$field]);
        }
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../images/avatars/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true); 
            }
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $fieldsToUpdate[] = "profile_picture = ?";
                $params[] = $dest_path;
                $message .= ' File is successfully uploaded.';
            } else {
                $message .= ' There was some error moving the file to upload directory.';
            }
        } else {
            $message .= ' Upload failed. Allowed file types: JPG, GIF, PNG, JPEG.';
        }
    }

    // Check for password update
    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['password'] === $_POST['confirm_password']) {
            $fieldsToUpdate[] = "user_password = ?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            $message .= ' Passwords do not match.';
        }
    }

    if (!empty($fieldsToUpdate)) {
        $sql .= implode(', ', $fieldsToUpdate) . " WHERE id = ?";
        $params[] = $_SESSION['user_id'];

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $message .= ' Account information updated successfully!';
        } catch (PDOException $e) {
            $message .= ' Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information - Elite Finds</title>
    <link rel="stylesheet" href="../css/account_info.css">
</head>
<body>
    <div class="form-account">
        <div class="logo-account">
            <span class="main-logo">ELITE FINDS</span>
            <span class="tagline">your luxury second-hand bazaar</span>
        </div>
        <h2>Update Your Account Information</h2>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="first_name" placeholder="First Name">
        <input type="text" name="last_name" placeholder="Last Name">
        <input type="email" name="email" placeholder="Email Address">
        <input type="text" name="username" placeholder="Username">
        <input type="text" name="address" placeholder="Address">
        <input type="file" name="profile_picture" accept="image/*">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <button type="submit">Update Info</button>
        </form>
        <p><a href="user_page.php">Return to Profile</a></p>
    </div>
</body>
</html>
