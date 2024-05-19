<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    $sellerId = filter_input(INPUT_POST, 'seller_id', FILTER_VALIDATE_INT);
    $reviewerId = $_SESSION['user_id'];

    if (!$itemId || !$sellerId || !$reviewerId) {
        $_SESSION['error_message'] = "Invalid request.";
        header('Location: ../templates/user_page.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review - Elite Finds</title>
    <link rel="stylesheet" href="../css/review.css">
</head>
<body>
    <header>
        <div class="top-bar">
            <span class="logo"><a href="../index.php">ELITE FINDS</a></span>
        </div>
    </header>
    <main>
        <div class="review-form-container">
            <h2>Add Review</h2>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            <form action="submit_review.php" method="post">
                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($itemId); ?>">
                <input type="hidden" name="seller_id" value="<?php echo htmlspecialchars($sellerId); ?>">
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <option value="">Select a rating</option>
                        <option value="1">1 - Very Bad</option>
                        <option value="2">2 - Bad</option>
                        <option value="3">3 - Average</option>
                        <option value="4">4 - Good</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Comment:</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>
                <button type="submit">Submit Review</button>
            </form>
        </div>
    </main>
</body>
</html>
