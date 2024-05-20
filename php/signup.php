<?php
$pdo = new PDO('sqlite:../database/database.db');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $address = htmlspecialchars($_POST['address']);
    $password = htmlspecialchars($_POST['password']);
    $confirmPassword = htmlspecialchars($_POST['confirm-password']);

    if ($password !== $confirmPassword) {
        die('Passwords do not match.');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO User (first_name, last_name, email, username, user_password, user_address) VALUES (?, ?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$fname, $lname, $email, $username, $hashed_password, $address]);
        echo "User registered successfully!";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
