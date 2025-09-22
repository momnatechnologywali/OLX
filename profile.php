<?php
// profile.php
// User profile management, view own listings
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
// Fetch user info
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$_SESSION['user_id']]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);
 
// Fetch own listings
$stmt_listings = $pdo->prepare("SELECT l.*, c.name AS category_name FROM listings l JOIN categories c ON l.category_id = c.id WHERE l.user_id = ? ORDER BY l.created_at DESC");
$stmt_listings->execute([$_SESSION['user_id']]);
$listings = $stmt_listings->fetchAll(PDO::FETCH_ASSOC);
 
// Update profile if POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $stmt_update = $pdo->prepare("UPDATE users SET name = ?, phone = ?, location = ? WHERE id = ?");
    $stmt_update->execute([$name, $phone, $location, $_SESSION['user_id']]);
    $_SESSION['user_name'] = $name;
    echo "<script>alert('Profile updated!'); window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - OLX Clone</title>
    <style>
        /* Internal CSS - Clean and responsive */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #002f34; }
        form input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        form button { padding: 10px; background-color: #002f34; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .listings { margin-top: 40px; }
        .listing { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .listing a { color: #002f34; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profile</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            <input type="text" name="location" value="<?php echo htmlspecialchars($user['location']); ?>">
            <button type="submit">Update Profile</button>
        </form>
        <div class="listings">
            <h3>Your Listings</h3>
            <?php foreach ($listings as $listing): ?>
                <div class="listing">
                    <h4><?php echo htmlspecialchars($listing['title']); ?></h4>
                    <p>Status: <?php echo $listing['status']; ?></p>
                    <a href="edit_ad.php?id=<?php echo $listing['id']; ?>">Edit</a> |
                    <a href="delete_ad.php?id=<?php echo $listing['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> |
                    <?php if ($listing['status'] == 'active'): ?>
                        <a href="mark_sold.php?id=<?php echo $listing['id']; ?>">Mark as Sold</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="view_messages.php">View Messages</a>
    </div>
    <script>
        // Internal JS
        console.log('Profile page loaded');
    </script>
</body>
</html>
