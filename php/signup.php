<?php
// Connection to the SQLite database
$pdo = new PDO('sqlite:../database/database.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $address = htmlspecialchars($_POST['address']);
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['confirm-password']);

    // Check if passwords match
    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert data
    $stmt = $pdo->prepare("INSERT INTO User (first_name, last_name, email, username, user_password, user_address) VALUES (?, ?, ?, ?, ?, ?)");

    // Execute statement
    try {
        $stmt->execute([$fname, $lname, $email, $username, $hashed_password, $address]);
        echo "User registered successfully!";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
