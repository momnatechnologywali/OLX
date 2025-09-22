<?php
// index.php
// Homepage displaying featured and recent listings with search bar
session_start();
include 'db.php';
 
// Fetch recent listings
$stmt_recent = $pdo->prepare("SELECT l.*, c.name AS category_name, u.name AS seller_name FROM listings l JOIN categories c ON l.category_id = c.id JOIN users u ON l.user_id = u.id WHERE l.status = 'active' ORDER BY l.created_at DESC LIMIT 10");
$stmt_recent->execute();
$recent_listings = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);
 
// Fetch featured (random active)
$stmt_featured = $pdo->prepare("SELECT l.*, c.name AS category_name, u.name AS seller_name FROM listings l JOIN categories c ON l.category_id = c.id JOIN users u ON l.user_id = u.id WHERE l.status = 'active' ORDER BY RAND() LIMIT 5");
$stmt_featured->execute();
$featured_listings = $stmt_featured->fetchAll(PDO::FETCH_ASSOC);
 
// Fetch categories
$stmt_categories = $pdo->prepare("SELECT * FROM categories");
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLX Clone - Homepage</title>
    <style>
        /* Internal CSS - Amazing, real-looking, professional design */
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333; }
        header { background-color: #002f34; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .search-bar { margin-bottom: 20px; }
        .search-bar form { display: flex; justify-content: center; }
        .search-bar input[type="text"] { width: 60%; padding: 10px; border: 1px solid #ccc; border-radius: 4px 0 0 4px; }
        .search-bar select { padding: 10px; border: 1px solid #ccc; }
        .search-bar button { padding: 10px 20px; background-color: #002f34; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer; }
        .section { margin-bottom: 40px; }
        .section h2 { color: #002f34; }
        .listings { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .listing { background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .listing:hover { transform: scale(1.05); }
        .listing img { width: 100%; height: 200px; object-fit: cover; }
        .listing-info { padding: 15px; }
        .listing-info h3 { margin: 0 0 10px; color: #002f34; }
        .listing-info p { margin: 5px 0; }
        .nav { margin: 20px 0; text-align: center; }
        .nav a { color: #002f34; text-decoration: none; margin: 0 10px; font-weight: bold; }
        .nav a:hover { text-decoration: underline; }
        footer { background-color: #002f34; color: white; text-align: center; padding: 10px; }
        @media (max-width: 768px) { .listings { grid-template-columns: 1fr; } .search-bar input[type="text"] { width: 100%; } .search-bar form { flex-direction: column; } .search-bar select, .search-bar button { width: 100%; margin-top: 10px; border-radius: 4px; } }
    </style>
</head>
<body>
    <header>
        <h1>OLX Clone</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> | <a href="profile.php" style="color: white;">Profile</a> | <a href="logout.php" style="color: white;">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php" style="color: white;">Login</a> | <a href="signup.php" style="color: white;">Signup</a></p>
        <?php endif; ?>
    </header>
    <div class="container">
        <div class="search-bar">
            <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search for products...">
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="nav">
            <a href="post_ad.php">Post Ad</a>
        </div>
        <div class="section">
            <h2>Featured Listings</h2>
            <div class="listings">
                <?php foreach ($featured_listings as $listing): ?>
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
        <div class="section">
            <h2>Recent Listings</h2>
            <div class="listings">
                <?php foreach ($recent_listings as $listing): ?>
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
    </div>
    <footer>
        <p>&copy; 2025 OLX Clone. All rights reserved.</p>
    </footer>
    <script>
        // Internal JS - No separate file
        console.log('Homepage loaded');
    </script>
</body>
</html>
