<?php
// edit_ad.php
// Edit existing ad
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$listing) {
    echo "<script>alert('Not found or not yours'); window.location.href = 'profile.php';</script>";
    exit;
}
 
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
    $image = $listing['image'];
 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }
 
    $stmt_update = $pdo->prepare("UPDATE listings SET title = ?, description = ?, price = ?, category_id = ?, image = ?, product_condition = ?, location = ? WHERE id = ?");
    $stmt_update->execute([$title, $description, $price, $category_id, $image, $product_condition, $location, $id]);
    echo "<script>alert('Ad updated!'); window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ad - OLX Clone</title>
    <style>
        /* Internal CSS - Similar to post ad */
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
        <h2>Edit Ad</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" value="<?php echo htmlspecialchars($listing['title']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($listing['description']); ?></textarea>
            <input type="number" name="price" value="<?php echo $listing['price']; ?>" step="0.01" required>
            <select name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $listing['category_id']) echo 'selected'; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="condition" required>
                <option value="new" <?php if ($listing['product_condition'] == 'new') echo 'selected'; ?>>New</option>
                <option value="used" <?php if ($listing['product_condition'] == 'used') echo 'selected'; ?>>Used</option>
            </select>
            <input type="text" name="location" value="<?php echo htmlspecialchars($listing['location']); ?>">
            <input type="file" name="image" accept="image/*"> (Current: <?php echo $listing['image']; ?>)
            <button type="submit">Update Ad</button>
        </form>
    </div>
    <script>
        // Internal JS
        console.log('Edit Ad page loaded');
    </script>
</body>
</html>
