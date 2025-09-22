<?php
// search.php
// Search and filters
session_start();
include 'db.php';
 
$query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$condition = isset($_GET['condition']) ? $_GET['condition'] : '';
 
$sql = "SELECT l.*, c.name AS category_name, u.name AS seller_name FROM listings l JOIN categories c ON l.category_id = c.id JOIN users u ON l.user_id = u.id WHERE l.status = 'active'";
$params = [];
 
if ($query) {
    $sql .= " AND (l.title LIKE ? OR l.description LIKE ?)";
    $params[] = "%$query%";
    $params[] = "%$query%";
}
if ($category) {
    $sql .= " AND l.category_id = ?";
    $params[] = $category;
}
if ($min_price) {
    $sql .= " AND l.price >= ?";
    $params[] = $min_price;
}
if ($max_price) {
    $sql .= " AND l.price <= ?";
    $params[] = $max_price;
}
if ($location) {
    $sql .= " AND l.location LIKE ?";
    $params[] = "%$location%";
}
if ($condition) {
    $sql .= " AND l.product_condition = ?";
    $params[] = $condition;
}
 
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// Fetch categories for filter
$stmt_categories = $pdo->prepare("SELECT * FROM categories");
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - OLX Clone</title>
    <style>
        /* Internal CSS - Search results grid */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .filters { margin-bottom: 20px; }
        .filters form { display: flex; flex-wrap: wrap; gap: 10px; }
        .filters input, .filters select { padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .filters button { padding: 10px 20px; background-color: #002f34; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .listings { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .listing { background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .listing img { width: 100%; height: 200px; object-fit: cover; }
        .listing-info { padding: 15px; }
        .listing-info h3 { margin: 0 0 10px; color: #002f34; }
        .listing-info p { margin: 5px 0; }
        @media (max-width: 768px) { .filters form { flex-direction: column; } .filters input, .filters select, .filters button { width: 100%; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="filters">
            <form method="GET">
                <input type="text" name="query" value="<?php echo htmlspecialchars($query); ?>" placeholder="Search...">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $category) echo 'selected'; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>" placeholder="Min Price">
                <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>" placeholder="Max Price">
                <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="Location">
                <select name="condition">
                    <option value="">All Conditions</option>
                    <option value="new" <?php if ($condition == 'new') echo 'selected'; ?>>New</option>
                    <option value="used" <?php if ($condition == 'used') echo 'selected'; ?>>Used</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="listings">
            <?php foreach ($results as $listing): ?>
                <div class="listing">
                    <?php if ($listing['image']): ?>
                        <img src="<?php echo htmlspecialchars($listing['image']); ?>" alt="<?php echo htmlspecialchars($listing['title']); ?>">
                    <?php endif; ?>
                    <div class="listing-info">
                        <h3><a href="view_listing.php?id=<?php echo $listing['id']; ?>"><?php echo htmlspecialchars($listing['title']); ?></a></h3>
                        <p>Price: $<?php echo number_format($listing['price'], 2); ?></p>
                        <p>Category: <?php echo htmlspecialchars($listing['category_name']); ?></p>
                        <p>Seller: <?php echo htmlspecialchars($listing['seller_name']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($listing['location']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        // Internal JS
        console.log('Search page loaded');
    </script>
</body>
</html>
