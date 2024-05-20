<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$pdo = new PDO('sqlite:../database/database.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id']; 

$query = "
    SELECT DISTINCT
        m.product_id,
        i.title AS product_title,
        m.to_user_id AS other_user_id,
        u.username AS other_username,
        MAX(m.created_at) AS last_message_time
    FROM messages m
    JOIN User u ON u.id = m.to_user_id OR u.id = m.from_user_id
    JOIN Item i ON i.id = m.product_id
    WHERE (m.from_user_id = :user_id OR m.to_user_id = :user_id) AND u.id != :user_id
    GROUP BY m.product_id, other_user_id
    ORDER BY last_message_time DESC;
";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Chats - Elite Finds</title>
    <link rel="stylesheet" href="../css/all_chats.css"> 
</head>
<body>
    <div class="chat-list-container">
        <h1>All Conversations</h1>
        <ul class="chat-list">
            <?php foreach ($chats as $chat): ?>
                <li>
                    <a href="chat.php?seller_id=<?php echo $chat['other_user_id']; ?>&product_id=<?php echo $chat['product_id']; ?>">
                        Chat with <?php echo htmlspecialchars($chat['other_username']); ?> about "<?php echo htmlspecialchars($chat['product_title']); ?>"
                    </a>
                    <span>Last message: <?php echo $chat['last_message_time']; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <footer>
        <div class="footer-section">
            <p>&copy;Elite Finds, 2024</p>
        </div>
    </footer>
</body>
</html>
