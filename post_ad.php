<?php
// post_ad.php
// Post new ad with image upload
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
// Fetch categories
$stmt_categories = $pdo->prepare("SELECT * FROM categories");
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $product_condition = $_POST['condition'];
    $location = $_POST['location'];
    $image = '';
 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }
 
    $stmt = $pdo->prepare("INSERT INTO listings (user_id, title, description, price, category_id, image, product_condition, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $description, $price, $category_id, $image, $product_condition, $location]);
    echo "<script>alert('Ad posted!'); window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Ad - OLX Clone</title>
    <style>
        /* Internal CSS - Form looks real and engaging */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #002f34; }
        form input, form textarea, form select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        form button { padding: 10px; background-color: #002f34; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        @media (max-width: 768px) { .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Post New Ad</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="condition" required>
                <option value="new">New</option>
                <option value="used">Used</option>
            </select>
            <input type="text" name="location" placeholder="Location">
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post Ad</button>
        </form>
    </div>
    <script>
        // Internal JS
        console.log('Post Ad page loaded');
    </script>
</body>
</html>
