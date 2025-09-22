<?php
// view_messages.php
// View communication history
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
// Fetch messages where user is sender or receiver
$stmt = $pdo->prepare("SELECT m.*, u_from.name AS from_name, u_to.name AS to_name, l.title AS listing_title FROM messages m 
                       JOIN users u_from ON m.from_user = u_from.id 
                       JOIN users u_to ON m.to_user = u_to.id 
                       JOIN listings l ON m.listing_id = l.id 
                       WHERE m.from_user = ? OR m.to_user = ? 
                       ORDER BY m.timestamp DESC");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - OLX Clone</title>
    <style>
        /* Internal CSS - Chat history style */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #002f34; }
        .message { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .message p { margin: 5px 0; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Messages</h2>
        <?php foreach ($messages as $msg): ?>
            <div class="message">
                <p><strong>From:</strong> <?php echo htmlspecialchars($msg['from_name']); ?></p>
                <p><strong>To:</strong> <?php echo htmlspecialchars($msg['to_name']); ?></p>
                <p><strong>Listing:</strong> <?php echo htmlspecialchars($msg['listing_title']); ?></p>
                <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                <p><strong>Time:</strong> <?php echo $msg['timestamp']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        // Internal JS
        console.log('Messages page loaded');
    </script>
</body>
</html>
