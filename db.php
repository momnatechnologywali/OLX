<?php
// db.php
// Database connection file using PDO for pro level handling
 
$host = 'localhost'; // Assuming localhost, change if needed
$dbname = 'dbhkt501b8gumr';
$username = 'uws1gwyttyg2r';
$password = 'k1tdlhq4qpsf';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
