<?php
  /*
  declare(strict_types = 1);

  require_once(__DIR__ . '/../session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.php');
  require_once(__DIR__ . '/../database/user_class.php');

  $db = getDatabaseConnection();

  $customer = Customer::getCustomerWithPassword($db, $_POST['email'], $_POST['password']);

  if ($customer) {
    $session->setId($customer->id);
    $session->setName($customer->name());
    $session->addMessage('success', 'Login successful!');
  } else {
    $session->addMessage('error', 'Invalid username or password.');
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);

*/


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
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['profile_picture'] = $user->getProfilePicture();

        header('Location: ../templates/user_page.php');  // Adjust redirect as needed
        exit;
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Invalid request method."; // This message is shown when not a POST request
}
?>
