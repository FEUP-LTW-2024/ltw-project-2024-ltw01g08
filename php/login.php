<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Process login
    $pdo = new PDO('sqlite:../database/database.db');
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_picture'] = $user['profile_picture']; // Adjust this as necessary

        header('Location: ../templates/user_page.php');  // Adjust redirect as needed
        exit;
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid request method."; // This message is shown when not a POST request
}
?>
